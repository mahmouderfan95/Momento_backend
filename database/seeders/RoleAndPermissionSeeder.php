<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            [
                'name' => 'dashboard', // Translated permission name
                'display_name' => 'الاحصائيات'
            ],
            [
                'name' => 'show-categories', // Translated permission name
                'display_name' => 'عرض الاقسام',
            ],
            [
                'name' => 'add-category', // Translated permission name
                'display_name' => 'اضافة قسم',
            ],
            [
                'name' => 'edit-category', // Translated permission name
                'display_name' => 'تعديل قسم'
            ],
            [
                'name' => 'delete-category', // Translated permission name
                'display_name' => 'حذف قسم'
            ],
            [
                'name' => 'admins-reports', // Translated permission name
                'display_name' => 'تقرير المشرفين'
            ],
            [
                'name' => 'products-reports', // Translated permission name
                'display_name' => 'تقرير المنتجات'
            ],
            [
                'name' => 'customers-reports', // Translated permission name
                'display_name' => 'تقرير العملاء'
            ],
            [
                'name' => 'show-admins', // Translated permission name
                'display_name' => 'عرض المستخدمين'
            ],
            [
                'name' => 'add-admins', // Translated permission name
                'display_name' => 'اضافة مستخدم'
            ],
            [
                'name' => 'edit-admins', // Translated permission name
                'display_name' => 'تعديل مستخدم'
            ],
            [
                'name' => 'delete-admins', // Translated permission name
                'display_name' => 'حذف مستخدم'
            ],
            [
                'name' => 'show-products', // Translated permission name
                'display_name' => 'عرض المنتجات'
            ],
            [
                'name' => 'add-products', // Translated permission name
                'display_name' => 'اضافة منتج'
            ],
            [
                'name' => 'edit-products', // Translated permission name
                'display_name' => 'تعديل منتج'
            ],
            [
                'name' => 'delete-products', // Translated permission name
                'display_name' => 'حذف منتج'
            ],
            [
                'name' => 'show-attributes',
                'display_name' => 'عرض خصائص المنتج'
            ],
            [
                'name' => 'add-attributes',
                'display_name' => 'اضافة خاصية منتج'
            ],
            [
                'name' => 'edit-attributes',
                'display_name' => 'تعديل خاصية المنتج'
            ],
            [
                'name' => 'delete-attributes',
                'display_name' => 'حذف خاصية المنتج'
            ],
            [
                'name' => 'show-coupons',
                'display_name' => 'عرض الكوبونات'
            ],
            [
                'name' => 'add-coupons',
                'display_name' => 'اضافة كوبون'
            ],
            [
                'name' => 'edit-coupons',
                'display_name' => 'تعديل الكوبون'
            ],
            [
                'name' => 'delete-coupons',
                'display_name' => 'حذف الكوبون'
            ],
            [
                'name' => 'show-customers',
                'display_name' => 'عرض العملاء'
            ],
            [
                'name' => 'add-customers',
                'display_name' => 'اضافة عميل'
            ],
            [
                'name' => 'edit-customers',
                'display_name' => 'تعديل عميل'
            ],
            [
                'name' => 'delete-customers',
                'display_name' => 'حذف عميل'
            ],
            [
                'name' => 'show-orders',
                'display_name' => 'عرض الطلبات'
            ],
            [
                'name' => 'add-orders',
                'display_name' => 'اضافة طلب'
            ],
            [
                'name' => 'show-order-details',
                'display_name' => 'عرض تفاصيل الطلب'
            ],
            [
                'name' => 'update-orders',
                'display_name' => 'تعديل الطلب'
            ],
            [
                'name' => 'delete-orders',
                'display_name' => 'حذف الطلب'
            ],
            [
                'name' => 'show-shipping-companies',
                'display_name' => 'عرض شركات الشحن'
            ],
            [
                'name' => 'add-shipping-companies',
                'display_name' => 'اضافة شركة شحن'
            ],
            [
                'name' => 'edit-shipping-companies',
                'display_name' => 'تعديل شركة شحن'
            ],
            [
                'name' => 'delete-shipping-companies',
                'display_name' => 'حذف شركة شحن'
            ],
            [
                'name' => 'bulk-assign-shipping-companies',
                'display_name' => 'تعيين شركات الشحن'
            ],
            [
                'name' => 'show-sliders',
                'display_name' => 'عرض الاسلايدر'
            ],
            [
                'name' => 'show-sliders-details',
                'display_name' => 'عرض تفاصيل الاسلايدر'
            ],
            [
                'name' => 'add-sliders',
                'display_name' => 'اضافة اسلايدر'
            ],
            [
                'name' => 'update-sliders',
                'display_name' => 'تعديل الاسلايدر'
            ],
            [
                'name' => 'delete-sliders',
                'display_name' => 'حذف الاسلايدر'
            ],
            [
                'name' => 'show-countries',
                'display_name' => 'عرض الدول'
            ],
            [
                'name' => 'add-countries',
                'display_name' => 'اضافة دولة'
            ],
            [
                'name' => 'update-countries',
                'display_name' => 'تعديل الدولة'
            ],
            [
                'name' => 'delete-countries',
                'display_name' => 'حذف الدولة'
            ],
            [
                'name' => 'show-cities',
                'display_name' => 'عرض المدن'
            ],
            [
                'name' => 'add-cities',
                'display_name' => 'اضافة مدينة'
            ],
            [
                'name' => 'update-cities',
                'display_name' => 'تعديل المدينة'
            ],
            [
                'name' => 'delete-cities',
                'display_name' => 'حذف المدينة'
            ],
            [
                'name' => 'show-areas',
                'display_name' => 'عرض المناطق'
            ],
            [
                'name' => 'add-areas',
                'display_name' => 'اضافة منطقة'
            ],
            [
                'name' => 'update-areas',
                'display_name' => 'تعديل المنطقة'
            ],
            [
                'name' => 'delete-areas',
                'display_name' => 'حذف المنطقة'
            ],
            [
                'name' => 'show-wallet-numbers',
                'display_name' => 'عرض المحافظ'
            ],
            [
                'name' => 'add-wallet-numbers',
                'display_name' => 'اضافة محفظة'
            ],
            [
                'name' => 'update-wallet-numbers',
                'display_name' => 'تعديل المحفظة'
            ],
            [
                'name' => 'delete-wallet-numbers',
                'display_name' => 'حذف المحفظة'
            ],
            [
                'name' => 'show-bundles',
                'display_name' => 'مشاهدة العروض'
            ],
            [
                'name' => 'add-bundles',
                'display_name' => 'اضافة عرض'
            ],
            [
                'name' => 'update-bundles',
                'display_name' => 'تعديل العرض'
            ],
            [
                'name' => 'delete-bundles',
                'display_name' => 'حذف العرض'
            ],
            [
                'name' => 'show-reviews',
                'display_name' => 'مشاهدة التقييمات'
            ],
            [
                'name' => 'add-reviews',
                'display_name' => 'اضافة تقييم'
            ],
            [
                'name' => 'update-reviews',
                'display_name' => 'تعديل التقييم'
            ],
            [
                'name' => 'delete-reviews',
                'display_name' => 'حذف التقييم'
            ],
            [
                'name' => 'show-results',
                'display_name' => 'مشاهدة النتائج'
            ],
            [
                'name' => 'add-results',
                'display_name' => 'اضافة نتائج'
            ],
            [
                'name' => 'update-results',
                'display_name' => 'تعديل النتائج'
            ],
            [
                'name' => 'delete-results',
                'display_name' => 'حذف النتائج'
            ],
            [
                'name' => 'show-roles',
                'display_name' => 'عرض الصلاحيات'
            ],
            [
                'name' => 'add-roles',
                'display_name' => 'اضافة صلاحية'
            ],
            [
                'name' => 'update-roles',
                'display_name' => 'تعديل الصلاحية'
            ],
            [
                'name' => 'delete-roles',
                'display_name' => 'حذف الصلاحيات'
            ],
            [
                'name' => 'update-settings',
                'display_name' => 'تحديث اعدادات الموقع'
            ],
        ];
        foreach ($permissions as $permission) {
            Permission::query()->updateOrCreate(
                ['name' => $permission['name']], // Unique key
                [
                    'name' => $permission['name'],
                    'display_name' => $permission['display_name'],
                    'guard_name' => 'admin',
                ]
            );
        }
        $admin = Admin::query()->find(1);
        // Create the super-admin role
        $adminRole = Role::query()->updateOrCreate(
            ['name' => 'super-admin'], // Unique key
            [
                'name' => 'super-admin',
                'guard_name' => 'admin',
            ]
        );

        // Assign all permissions to the super-admin role
        $adminRole->syncPermissions(Permission::all());
        $admin->syncRoles('super-admin');
        $admin->syncPermissions(collect($permissions)->pluck('name')->toArray());

        $this->command->info('Permissions and super-admin role seeded successfully.');
    }
}
