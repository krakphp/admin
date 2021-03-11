<?php

namespace Demo\App\Catalog\UI\Tests;

use Demo\App\AppKernel;
use Demo\App\Catalog\Domain\Tests\SizeScaleSteps;
use Demo\App\Catalog\Domain\SizeScaleTest;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Krak\Fun\{f, c};

final class SizeScaleListViewTest extends WebTestCase
{
    private $sizeScaleSteps;
    private $client;

    protected static function getKernelClass() {
        return AppKernel::class;
    }

    protected function setUp(): void {
        parent::setUp();
        $this->client = self::createClient();
        $this->sizeScaleSteps = SizeScaleSteps::fromContainer(self::$container);
    }

    /** @test */
    public function can_show_no_results() {
        $this->sizeScaleSteps->given_the_following_size_scales([]);
        $this->when_the_page_is_visited('/admin/size-scales');
        $this->then_the_table_at_row_matches(0, ['No Results']);
    }

    /** @test */
    public function can_show_size_scales() {
        $this->sizeScaleSteps->given_the_following_size_scales([
            SizeScaleTest::draftSizeScale('Mens', ['1', '2']),
            SizeScaleTest::draftSizeScale('Kids', ['1', '2', '3']),
        ]);
        $this->when_the_page_is_visited('/admin/size-scales');
        $this->then_the_table_at_row_matches(0, [1, 'Mens', 'draft', null, '1, 2']);
        $this->then_the_table_at_row_matches(1, [2, 'Kids', 'draft', null, '1, 2, 3']);
        $this->then_the_table_has_num_rows(2);
    }

    /** @test */
    public function size_scale_sizes_are_shown_sorted() {
        $this->sizeScaleSteps->given_the_following_size_scales([
            SizeScaleTest::draftSizeScale('Mens', ['3', '1', '2']),
        ]);
        $this->when_the_page_is_visited('/admin/size-scales');
        $this->then_the_table_at_row_matches(0, [1, 'Mens', 'draft', null, '1, 2, 3']);
    }

    /** @test */
    public function can_search_for_size_scales() {
        $this->sizeScaleSteps->given_the_following_size_scales([
            SizeScaleTest::draftSizeScale('Mens', ['3', '1', '2']),
            SizeScaleTest::draftSizeScale('Kids', ['1', '2']),
        ]);
        $this->when_the_page_is_visited('/admin/size-scales', ['search' => 'Kids']);
        $this->then_the_table_at_row_matches(0, [2, 'Kids', 'draft', null, '1, 2']);
    }

    /** @test */
    public function can_search_for_size_scales_by_status() {
        $this->sizeScaleSteps->given_the_following_size_scales([
            SizeScaleTest::publishedSizeScale('Mens', ['3', '1', '2']),
            SizeScaleTest::draftSizeScale('Kids', ['1', '2']),
        ]);
        $this->when_the_page_is_visited('/admin/size-scales', ['search' => 'published']);
        $this->then_the_table_at_row_matches(0, [1, 'Mens', 'published', null, '1, 2, 3']);
        $this->then_the_table_has_num_rows(1);
    }

    /** test */
    public function can_sort_by_id() {}

    /** test */
    public function can_sort_by_name() {}

    /** test */
    public function can_page_entries() {}

    /** test */
    public function allows_deleting_size_scales() {}

    private function when_the_page_is_visited(string $uri, array $queryParams = []) {
        $this->client->request('GET', $uri, $queryParams);
    }

    private function then_the_table_at_row_matches(int $row, array $colValues) {
        $tableRows = $this->client->getCrawler()->filter(sprintf('table tbody tr:nth-child(%d)', $row + 1));
        $tdElements = $tableRows->children('td');

        $this->assertGreaterThanOrEqual(count($colValues), count($tdElements), 'Table row has fewer columns than expected.');
        foreach (f\zip($colValues, $tdElements) as [$colValue, $el]) {
            if ($colValue === null) {
                continue; // skip nulls
            }
            $this->assertEquals($colValue, (new Crawler($el))->text());
        }
    }

    private function then_the_table_has_num_rows($totalRows) {
        $this->assertCount($totalRows, $this->client->getCrawler()->filter('table tbody tr'));
    }

}
