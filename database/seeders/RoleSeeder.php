<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => 'CREATE_PROSPECT']);
        Permission::create(['name' => 'EDIT_PROSPECT']);
        Permission::create(['name' => 'DELETE_PROSPECT']);
        Permission::create(['name' => 'VIEW_PROSPECT']);
        Permission::create(['name' => 'VIEW_ALL_PROSPECT']);

        Permission::create(['name' => 'CREATE_QUOTATION']);
        Permission::create(['name' => 'EDIT_QUOTATION']);
        Permission::create(['name' => 'DELETE_QUOTATION']);
        Permission::create(['name' => 'VIEW_QUOTATION']);

        Permission::create(['name' => 'ADD_QUOTATION_ITEM']);
        Permission::create(['name' => 'EDIT_QUOTATION_ITEM']);
        Permission::create(['name' => 'REMOVE_QUOTATION_ITEM']);

        Permission::create(['name' => 'CREATE_CLIENT']);
        Permission::create(['name' => 'EDIT_CLIENT']);
        Permission::create(['name' => 'DELETE_CLIENT']);
        Permission::create(['name' => 'VIEW_CLIENT']);

        Permission::create(['name' => 'CREATE_USER']);
        Permission::create(['name' => 'EDIT_USER']);
        Permission::create(['name' => 'DELETE_USER']);
        Permission::create(['name' => 'VIEW_USER']);

        Permission::create(['name' => 'FOLLOWUP_PROSPECT']);

        Permission::create(['name' => 'CREATE_PRODUCT']);
        Permission::create(['name' => 'EDIT_PRODUCT']);
        Permission::create(['name' => 'DELETE_PRODUCT']);
        Permission::create(['name' => 'VIEW_PRODUCT']);
        Permission::create(['name' => 'VIEW_ALL_INFO_PRODUCT']);

        Permission::create(['name' => 'VIEW_PROJECT']);
        Permission::create(['name' => 'CREATE_PROJECT']);
        Permission::create(['name' => 'EDIT_PROJECT']);
        Permission::create(['name' => 'DELETE_PROJECT']);
        Permission::create(['name' => 'VIEW_ALL_PROJECT']);

        Permission::create(['name' => 'VIEW_SETTING']);
        Permission::create(['name' => 'EDIT_SETTING']);
        Permission::create(['name' => 'VIEW_SALES_TARGET']);

        Permission::create(['name' => 'VIEW_STOCK']);
        Permission::create(['name' => 'VIEW_INVENTORY']);
        Permission::create(['name' => 'VIEW_PROJECT_ORDER']);
        Permission::create(['name' => 'VIEW_BORROWING']);

        Permission::create(['name' => 'VIEW_DELIVERY']);
        Permission::create(['name' => 'LOGISTIC_ORDER']);
        Permission::create(['name' => 'PERHITUNGAN_PROJECT']);

        Role::create(['name' => 'SUPER_ADMIN'])->givePermissionTo(Permission::all());
        Role::create(['name' => 'BOD'])->givePermissionTo([

            'VIEW_PROSPECT',
            'CREATE_PROSPECT',
            'EDIT_PROSPECT',
            'DELETE_PROSPECT',

            'CREATE_QUOTATION',
            'EDIT_QUOTATION',
            'DELETE_QUOTATION',
            'VIEW_QUOTATION',

            'CREATE_CLIENT',
            'EDIT_CLIENT',
            'DELETE_CLIENT',
            'VIEW_CLIENT',

            'CREATE_USER',
            'EDIT_USER',
            'DELETE_USER',
            'VIEW_USER',

            'FOLLOWUP_PROSPECT',
            'ADD_QUOTATION_ITEM',
            'EDIT_QUOTATION_ITEM',
            'REMOVE_QUOTATION_ITEM',

            'VIEW_PRODUCT',
            'CREATE_PRODUCT',
            'EDIT_PRODUCT',
            'DELETE_PRODUCT',
            'VIEW_ALL_INFO_PRODUCT',
            'VIEW_SETTING',
            'VIEW_SALES_TARGET',

            'VIEW_ALL_PROSPECT',
            'VIEW_ALL_PROJECT',
            'VIEW_PRODUCT',
            'EDIT_PRODUCT',
            'CREATE_PRODUCT',
            'DELETE_PRODUCT',
            'VIEW_DELIVERY',
            'LOGISTIC_ORDER',
            'PERHITUNGAN_PROJECT',

        ]);
        Role::create(['name' => 'SALES'])->givePermissionTo([

            'VIEW_PROSPECT',
            'CREATE_PROSPECT',
            'EDIT_PROSPECT',
            'DELETE_PROSPECT',

            'CREATE_QUOTATION',
            'EDIT_QUOTATION',
            'DELETE_QUOTATION',
            'VIEW_QUOTATION',

            'FOLLOWUP_PROSPECT',
            'ADD_QUOTATION_ITEM',
            'EDIT_QUOTATION_ITEM',
            'REMOVE_QUOTATION_ITEM',

            'VIEW_PRODUCT',
        ]);

        Role::create(['name' => 'PROJECT'])->givePermissionTo([

            'VIEW_PROJECT',
            'CREATE_PROJECT',
            'EDIT_PROJECT',
            'DELETE_PROJECT',

        ]);

        Role::create(['name' => 'LOGISTIC'])->givePermissionTo([
            'VIEW_STOCK',
            'VIEW_INVENTORY',
            'VIEW_PROJECT_ORDER',
            'VIEW_BORROWING',
            'VIEW_DELIVERY',

        ]);
        Role::create(['name' => 'FINANCE'])->givePermissionTo([
            'LOGISTIC_ORDER',
            'PERHITUNGAN_PROJECT',
            'VIEW_PRODUCT',
            'VIEW_ALL_INFO_PRODUCT',
        ]);

    }
}
