<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Management User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <!-- <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}
                </div> -->
                <div class="m-4 text-white"> 
                    <x-table.table1 class="table1">
                        <x-slot name="header">
                            <x-table.table-head>Nama</x-table.table-head>
                            <x-table.table-head>Email</x-table.table-head>
                            <x-table.table-head>Aksi</x-table.table-head>
                        </x-slot> 

                        @foreach($users as $user)
                            <x-table.table-body-row>
                                <x-table.table-body-td>{{ $user->name }}</x-table.table-body-td>
                                <x-table.table-body-td>{{ $user->email }}</x-table.table-body-td>
                                
                                <x-table.table-body-td>
                                    <a href="#" class="font-medium text-blue-600 hover:underline">Edit</a>
                                </x-table.table-body-td>
                            </x-table.table-body-row>
                        @endforeach
                    </x-table.table1>              
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
