<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermisssionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions =
        [
            'الفواتير',
            'قائمة الفواتير',
            'الفواتير المدفوعة',
            'الفواتير المدفوعة جزئيا',
            'الفواتير الغير مدفوعة',
            'إرشيف الفواتير',
            'التقارير',
            'تقرير الفواتير',
            'تقرير العملاء',
            'المستخدمين',
            'قائمة المستخدمين',
            'صلاحيات المستخدمين',
            'الإعدادات',
            'المنتجات',
            'الاقسام',

            'إضافة فاتورة',
            'حذف الفاتورة',
            'تصدير EXCEL',
            'تغير حالة الدفع',
            'تعديل الفاتورة',
            'أرشفة الفاتورة',
            'طباعةالفاتورة',
            'إضافة مرفق',
            'حذف المرفق',

            'إضافة مستخدم',
            'تعديل مستخدم',
            'حذف مستخدم',

            'عرض صلاحية',
            'إضافة صلاحية',
            'تعديل صلاحية',
            'حذف صلاحية',

            'إضافة منتج',
            'تعديل منتج',
            'حذف منتج',

            'إضافة قسم',
            'تعديل قسم',
            'حذف قسم',
            'الإشعارات',
        ];

        foreach ($permissions as $permission)
        {
            Permission::create(['name' => $permission]);
        }

    }
}