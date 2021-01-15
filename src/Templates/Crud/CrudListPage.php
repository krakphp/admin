<?php

namespace Krak\Admin\Templates\Crud;

use Krak\Admin\Templates\Layout\OneColumnLayout;
use Krak\Admin\Templates\Table;
use Krak\Admin\Templates\Typography;
use League\Plates\Component;
use function League\Plates\p;

final class CrudListPage extends Component
{
    public function __invoke(): void {
        echo (new OneColumnLayout(function() {
        ?>  <h1 class="font-medium text-2xl">Order Listing</h1>
            <div class="flex justify-between">
                <div></div>
                <div class="flex space-x-2">
                    <?=Typography::Button('Add', 'success')?>
                    <?=Typography::Button('Export')?>
                </div>
            </div>

            <?=Table::Wrapper(function() {
                ?>  <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-pink-200">
                        <thead>
                        <tr>
                            <?=Table::Th('Name')?>
                            <?=Table::Th('Title')?>
                            <?=Table::Th('Status')?>
                            <?=Table::Th('Role')?>
                            <?=Table::Th(function() {
                                ?> <span class="sr-only">Edit</span> <?php
                            })?>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full" src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=4&amp;w=256&amp;h=256&amp;q=60" alt="">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            Jane Cooper
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            jane.cooper@example.com
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">Regional Paradigm Technician</div>
                                <div class="text-sm text-gray-500">Optimization</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                              Active
                                            </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                Admin
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                <a href="#" class="text-blue-500 hover:text-blue-600 hover:underline">View</a>
                                <a href="#" class="text-blue-500 hover:text-blue-600 hover:underline">Edit</a>
                            </td>
                        </tr>
                        <!-- More rows... -->
                        </tbody>
                    </table>
                </div> <?php
            })?> <?php
        }));
    }
}
