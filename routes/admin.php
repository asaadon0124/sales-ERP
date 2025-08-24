<?php

use Livewire\Livewire;
use App\Models\Treasuries;
use Illuminate\Support\Facades\Route;
use App\Livewire\BackEnd\Treasuries\Create;
use App\Http\Controllers\Admin\PDFController;
use App\Http\Controllers\PDFExportController;
use App\Http\Controllers\Admin\adminsController;
use App\Http\Controllers\Admin\ShiftsController;
use App\Http\Controllers\backEnd\AuthController;
use App\Http\Controllers\BackEnd\ItemsController;
use App\Http\Controllers\backEnd\rolesController;
use App\Http\Controllers\backEnd\StoresController;
use App\Http\Controllers\Admin\MoveTypesController;
use App\Http\Controllers\backEnd\rebortsController;
use App\Http\Controllers\backEnd\AccountsController;
use App\Http\Controllers\backEnd\servantsController;
use App\Http\Controllers\Admin\SalesOrdersController;
use App\Http\Controllers\backEnd\CustomersController;
use App\Http\Controllers\backEnd\ItemUnitsController;
use App\Http\Controllers\backEnd\suppliersController;
use App\Livewire\BackEnd\Items\Create as ItemsCreate;
use App\Http\Controllers\backEnd\TreasuriesController;
use App\Http\Controllers\backEnd\permissionsController;
use App\Http\Controllers\backEnd\ItemCategoryController;
use App\Http\Controllers\backEnd\AccountsTypesController;
use App\Http\Controllers\backEnd\adminSittingsController;
use App\Http\Controllers\backEnd\materialTypesController;
use App\Http\Controllers\backEnd\PurchaseOrdersController;
use App\Http\Controllers\backEnd\actionHistoriesController;
use App\Http\Controllers\backEnd\AdminTreasuriesController;
use App\Http\Controllers\Admin\TreasuryTransactionController;
use App\Http\Controllers\backEnd\suppliersCategoryController;
use App\Http\Controllers\Admin\ItemCardMovementTypesController;
use App\Http\Controllers\Admin\ItemCardMovementCategoryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function ()
// {
//     return view('auth.backEnd.login');
// });


Livewire::setUpdateRoute(function ($handle)
{
    return Route::post('/livewire/update', $handle);
});


Route::prefix('admin')->group(function()
{
    Route::middleware('guest:admin')->group(function ()
    {
        Route::get('/', [AuthController::class, 'login'])->name('backEnd.login');
        Route::post('/makeLogin', [AuthController::class, 'makeLogin'])->name('backEnd.makeLogin');
    });



    Route::middleware('auth:admin')->group(function()
    {
        Route::get('/dashBoard', [AuthController::class, 'dashBoard'])->name('backEnd.dashBoard');
        Route::get('/logout', [AuthController::class, 'logout'])->name('backEnd.logout');

        Route::prefix('adminSittings')->group(function()
        {
            Route::get('/', [adminSittingsController::class, 'index'])->name('adminSittings.index');
            Route::get('/edit/{id}', [adminSittingsController::class, 'edit'])->name('adminSittings.edit');
            Route::put('/update/{id}', [adminSittingsController::class, 'update'])->name('adminSittings.update');
        });

        Route::prefix('actionHistory')->group(function()
        {
            Route::get('/', [actionHistoriesController::class, 'index'])->name('actionHistory.index');

        });


        Route::prefix('Treasuries_Shifts')->group(function()
        {
            Route::prefix('treasuries')->group(function()
            {
                Route::get('/', [TreasuriesController::class, 'index'])->name('treasuries.index');
                Route::get('/softDelete', [TreasuriesController::class, 'softDelete'])->name('treasuries.softDelete');
                Route::get('/show/{id}', function ($id)
                {
                    $item = Treasuries::find($id);
                    return view('backEnd.treasuries.show',compact('item'));
                })->name('treasuries.show');


                Route::get('/add_emplyee_treasuries', [AdminTreasuriesController::class, 'add_treasuries_to_employees'])->name('treasuries.add_emplyee_treasuries');
                Route::get('/admin_treasuries/show/{id}', [AdminTreasuriesController::class, 'show'])->name('treasuries.show_admin_treasury');
            });

            Route::prefix('shifts')->group(function()
            {
                Route::get('/', [ShiftsController::class, 'index'])->name('shifts.index');
                Route::get('/show/{id}', [ShiftsController::class, 'show'])->name('shifts.show');
            });
        });




           Route::prefix('items&stores')->group(function()
        {
            Route::prefix('itemUnits')->group(function()
            {
                Route::get('/', [ItemUnitsController::class, 'index'])->name('itemUnits.index');
                Route::get('/softDelete', [ItemUnitsController::class, 'softDelete'])->name('itemUnits.softDelete');

            });


            Route::prefix('itemCategories')->group(function()
            {
                Route::get('/', [ItemCategoryController::class, 'index'])->name('itemCategories.index');
                Route::get('/softDelete', [ItemCategoryController::class, 'softDelete'])->name('itemCategories.softDelete');
            });


            Route::prefix('items')->group(function()
            {
                Route::get('/', [ItemsController::class, 'index'])->name('items.index');
                Route::get('/create', [ItemsController::class, 'create'])->name('items.create');
                Route::get('/edit/{id}', [ItemsController::class, 'edit'])->name('items.edit');
                Route::get('/show/{id}', [ItemsController::class, 'show'])->name('items.show');
                Route::get('/softDelete', [ItemsController::class, 'softDelete'])->name('items.softDelete');
            });


            Route::prefix('stores')->group(function()
            {
                Route::get('/', [StoresController::class, 'index'])->name('stores.index');
                Route::get('/show/{id}', [StoresController::class, 'show'])->name('stores.show');
                Route::get('/softDelete', [StoresController::class, 'softDelete'])->name('stores.softDelete');
            });

             Route::prefix('ItemCardMovementTypes')->group(function()
            {
                Route::get('/', [ItemCardMovementTypesController::class, 'index'])->name('ItemCardMovementTypes.index');
                // Route::get('/softDelete', [ItemCardMovementTypesController::class, 'softDelete'])->name('ItemCardMovementTypes.softDelete');
            });


            Route::prefix('ItemCardMovementCategory')->group(function()
            {
                Route::get('/', [ItemCardMovementCategoryController::class, 'index'])->name('ItemCardMovementCategory.index');
                Route::get('/softDelete', [ItemCardMovementCategoryController::class, 'softDelete'])->name('ItemCardMovementCategory.softDelete');
            });



        });

        Route::prefix('Cash')->group(function()
        {
            Route::prefix('move_types')->group(function()
            {
                Route::get('/', [MoveTypesController::class, 'index'])->name('move_types.index');
                Route::get('/softDelete', [MoveTypesController::class, 'softDelete'])->name('move_types.softDelete');
            });



            Route::prefix('treasury_transations')->group(function()
            {
                Route::get('/', [TreasuryTransactionController::class, 'index'])->name('treasury_transations.index');
                Route::get('/index_pay', [TreasuryTransactionController::class, 'index_pay'])->name('treasury_transations.index_pay');
            });
        });







        Route::prefix('Accounts')->group(function()
        {
              Route::prefix('accounts_types')->group(function()
            {
                Route::get('/', [AccountsTypesController::class, 'index'])->name('accounts_types.index');
                Route::get('/softDelete', [AccountsTypesController::class, 'softDelete'])->name('accounts_types.softDelete');
            });


            Route::prefix('accounts')->group(function()
            {
                Route::get('/', [AccountsController::class, 'index'])->name('accounts.index');
                Route::get('/softDelete', [AccountsController::class, 'softDelete'])->name('accounts.softDelete');
            });


            Route::prefix('suppliersCategory')->group(function()
            {
                Route::get('/', [suppliersCategoryController::class, 'index'])->name('suppliersCategory.index');
                Route::get('/softDelete', [suppliersCategoryController::class, 'softDelete'])->name('suppliersCategory.softDelete');
            });


            Route::prefix('suppliers')->group(function()
            {
                Route::get('/', [suppliersController::class, 'index'])->name('suppliers.index');
                Route::get('/softDelete', [suppliersController::class, 'softDelete'])->name('suppliers.softDelete');
            });



            Route::prefix('customers')->group(function()
            {
                Route::get('/', [CustomersController::class, 'index'])->name('customers.index');
                Route::get('/softDelete', [CustomersController::class, 'softDelete'])->name('customers.softDelete');
            });


            Route::prefix('servants')->group(function()
            {
                Route::get('/', [servantsController::class, 'index'])->name('servants.index');
                Route::get('/softDelete', [servantsController::class, 'softDelete'])->name('servants.softDelete');
            });
        });




        Route::prefix('Permissions')->group(function()
        {
            Route::prefix('emoloyees')->group(function()
            {
                Route::get('/', [adminsController::class, 'index'])->name('emoloyees.index');
                Route::get('/show/{id}', [adminsController::class, 'show'])->name('emoloyees.show');
                Route::get('/softDelete', [adminsController::class, 'softDelete'])->name('emoloyees.softDelete');
            });

            Route::get('/', [permissionsController::class, 'index'])->name('permissions.index');
            Route::get('/roles', [rolesController::class, 'index'])->name('roles.index');

        });


        Route::prefix('Invoices')->group(function()
        {
            Route::prefix('matrial_types')->group(function()
            {
                Route::get('/', [materialTypesController::class, 'index'])->name('matrial_types.index');
            });


            Route::prefix('purchaseOrders')->group(function()
            {
                Route::get('/', [PurchaseOrdersController::class, 'index'])->name('purchaseOrders.index');
                Route::get('/show/{id}', [PurchaseOrdersController::class, 'show'])->name('purchaseOrders.show');

                Route::get('/index_returns', [PurchaseOrdersController::class, 'index_returns'])->name('purchaseOrders.index_returns');
                Route::get('/show_returns/{id}', [PurchaseOrdersController::class, 'show_returns'])->name('purchaseOrders.show_returns');
            });


            Route::prefix('salesOrder')->group(function()
            {
                Route::get('/', [SalesOrdersController::class, 'index'])->name('salesOrder.index');
                Route::get('/show/{id}', [SalesOrdersController::class, 'show'])->name('salesOrder.show');

                Route::get('/index_returns', [SalesOrdersController::class, 'index_returns'])->name('salesOrder.index_returns');
                Route::get('/show_returns/{id}', [SalesOrdersController::class, 'show_returns'])->name('salesOrder.show_returns');

            });
        });


         Route::prefix('Invoices')->group(function()
        {
            Route::prefix('matrial_types')->group(function()
            {
                Route::get('/', [materialTypesController::class, 'index'])->name('matrial_types.index');
            });


            Route::prefix('purchaseOrders')->group(function()
            {
                Route::get('/', [PurchaseOrdersController::class, 'index'])->name('purchaseOrders.index');
                Route::get('/show/{id}', [PurchaseOrdersController::class, 'show'])->name('purchaseOrders.show');

            });

            Route::prefix('Reborts')->group(function()
            {
                Route::get('/suppliers', [rebortsController::class, 'suppliers_reborts'])->name('Reborts.suppliers.index');
                Route::get('/customers', [rebortsController::class, 'customers_reborts'])->name('Reborts.customers.index');
                Route::get('/servants', [rebortsController::class, 'servants_reborts'])->name('Reborts.servants.index');
                Route::get('/employees', [rebortsController::class, 'employees_reborts'])->name('Reborts.employees.index');

                Route::get('/items', [rebortsController::class, 'items_reborts'])->name('Reborts.items.index');
                Route::get('/stores', [rebortsController::class, 'stores_reborts'])->name('Reborts.stores.index');


                Route::get('/invoice-pdf/{id}/{type}', [PDFController::class, 'downloadInvoice'])->name('invoice.download');

            });
        });

    });
});
