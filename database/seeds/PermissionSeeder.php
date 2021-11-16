<?php

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // Create Settings and its attributes
        Permission::create([
            'parent_id'     => null,
            'name'          => 'Settings',
            'slug'          => 'settings',
            'description'   => null,
        ]);
        $settings = Permission::where('slug', '=', 'settings')->first();
        $settingsAttributes = [
            [
                'parent_id'     => $settings->id,
                'name'          => 'View Settings',
                'slug'          => 'view-settings',
                'description'   => null,
            ],
            [
                'parent_id'     => $settings->id,
                'name'          => 'Manage NoticeBox',
                'slug'          => 'manage-noticebox',
                'description'   => null,
            ],
            [
                'parent_id'     => $settings->id,
                'name'          => 'Manage DMCA',
                'slug'          => 'manage-dmca',
                'description'   => null,
            ],
            [
                'parent_id'     => $settings->id,
                'name'          => 'Manage Rules',
                'slug'          => 'manage-rules',
                'description'   => null,
            ]
        ];
        foreach ($settingsAttributes as $settingsAttribute) {
            Permission::create([
                'parent_id'     => $settingsAttribute['parent_id'],
                'name'          => $settingsAttribute['name'],
                'slug'          => $settingsAttribute['slug'],
                'description'   => $settingsAttribute['description'],
                'created_at'    => \Carbon\Carbon::now(),
                'updated_at'    => \Carbon\Carbon::now(),
            ]);
        }


        // Create Manage User and its attributes
        Permission::create([
            'parent_id'     => null,
            'name'          => 'Manage User',
            'slug'          => 'manage-user',
            'description'   => null,
        ]);
        $manageUser = Permission::where('slug', '=', 'manage-user')->first();
        $manageUserAttributes = [
            [
                'parent_id'     => $manageUser->id,
                'name'          => 'Manage User List',
                'slug'          => 'manage-user-list',
                'description'   => null,
            ],[
                'parent_id'     => $manageUser->id,
                'name'          => 'Manage User Create',
                'slug'          => 'manage-user-create',
                'description'   => null,
            ],[
                'parent_id'     => $manageUser->id,
                'name'          => 'Manage User Show',
                'slug'          => 'manage-user-show',
                'description'   => null,
            ],[
                'parent_id'     => $manageUser->id,
                'name'          => 'Manage User Update',
                'slug'          => 'manage-user-update',
                'description'   => null,
            ],[
                'parent_id'     => $manageUser->id,
                'name'          => 'Manage User Delete',
                'slug'          => 'manage-user-delete',
                'description'   => null,
            ],
        ];
        foreach ($manageUserAttributes as $manageUserAttribute) {
            Permission::create([
                'parent_id'     => $manageUserAttribute['parent_id'],
                'name'          => $manageUserAttribute['name'],
                'slug'          => $manageUserAttribute['slug'],
                'description'   => $manageUserAttribute['description'],
                'created_at'    => \Carbon\Carbon::now(),
                'updated_at'    => \Carbon\Carbon::now(),
            ]);
        }


        // Create Manage Role and its attributes
        Permission::create([
            'parent_id'     => null,
            'name'          => 'Manage Role',
            'slug'          => 'manage-role',
            'description'   => null,
        ]);
        $manageRole = Permission::where('slug', '=', 'manage-role')->first();
        $manageRoleAttributes = [
            [
                'parent_id'     => $manageRole->id,
                'name'          => 'Manage Role List',
                'slug'          => 'manage-role-list',
                'description'   => null,
            ],[
                'parent_id'     => $manageRole->id,
                'name'          => 'Manage Role Create',
                'slug'          => 'manage-role-create',
                'description'   => null,
            ],[
                'parent_id'     => $manageRole->id,
                'name'          => 'Manage Role Show',
                'slug'          => 'manage-role-show',
                'description'   => null,
            ],[
                'parent_id'     => $manageRole->id,
                'name'          => 'Manage Role Update',
                'slug'          => 'manage-role-update',
                'description'   => null,
            ],[
                'parent_id'     => $manageRole->id,
                'name'          => 'Manage Role Delete',
                'slug'          => 'manage-role-delete',
                'description'   => null,
            ],
        ];
        foreach ($manageRoleAttributes as $manageRoleAttribute) {
            Permission::create([
                'parent_id'     => $manageRoleAttribute['parent_id'],
                'name'          => $manageRoleAttribute['name'],
                'slug'          => $manageRoleAttribute['slug'],
                'description'   => $manageRoleAttribute['description'],
                'created_at'    => \Carbon\Carbon::now(),
                'updated_at'    => \Carbon\Carbon::now(),
            ]);
        }


        // Create Manage Permission and its attributes
        Permission::create([
            'parent_id'     => null,
            'name'          => 'Manage Permission',
            'slug'          => 'manage-permission',
            'description'   => null,
        ]);
        $managePermission = Permission::where('slug', '=', 'manage-permission')->first();
        $managePermissionAttributes = [
            [
                'parent_id'     => $managePermission->id,
                'name'          => 'Manage Permission List',
                'slug'          => 'manage-permission-list',
                'description'   => null,
            ],[
                'parent_id'     => $managePermission->id,
                'name'          => 'Manage Permission Create',
                'slug'          => 'manage-permission-create',
                'description'   => null,
            ],[
                'parent_id'     => $managePermission->id,
                'name'          => 'Manage Permission Show',
                'slug'          => 'manage-permission-show',
                'description'   => null,
            ],[
                'parent_id'     => $managePermission->id,
                'name'          => 'Manage Permission Update',
                'slug'          => 'manage-permission-update',
                'description'   => null,
            ],[
                'parent_id'     => $managePermission->id,
                'name'          => 'Manage Permission Delete',
                'slug'          => 'manage-permission-delete',
                'description'   => null,
            ],
        ];
        foreach ($managePermissionAttributes as $managePermissionAttribute) {
            Permission::create([
                'parent_id'     => $managePermissionAttribute['parent_id'],
                'name'          => $managePermissionAttribute['name'],
                'slug'          => $managePermissionAttribute['slug'],
                'description'   => $managePermissionAttribute['description'],
                'created_at'    => \Carbon\Carbon::now(),
                'updated_at'    => \Carbon\Carbon::now(),
            ]);
        }


        // Create Manage Authorization and its attributes
        Permission::create([
            'parent_id'     => null,
            'name'          => 'Manage Authorization',
            'slug'          => 'manage-authorization',
            'description'   => null,
        ]);
        $manageAuthorization = Permission::where('slug', '=', 'manage-authorization')->first();
        $manageAuthorizationAttributes = [
            [
                'parent_id'     => $manageAuthorization->id,
                'name'          => 'Manage User Role',
                'slug'          => 'manage-user-role',
                'description'   => null,
            ],[
                'parent_id'     => $manageAuthorization->id,
                'name'          => 'Manage User Permission',
                'slug'          => 'manage-user-permission',
                'description'   => null,
            ],[
                'parent_id'     => $manageAuthorization->id,
                'name'          => 'Manage Role Permission',
                'slug'          => 'manage-role-permission',
                'description'   => null,
            ],
        ];
        foreach ($manageAuthorizationAttributes as $manageAuthorizationAttribute) {
            Permission::create([
                'parent_id'     => $manageAuthorizationAttribute['parent_id'],
                'name'          => $manageAuthorizationAttribute['name'],
                'slug'          => $manageAuthorizationAttribute['slug'],
                'description'   => $manageAuthorizationAttribute['description'],
                'created_at'    => \Carbon\Carbon::now(),
                'updated_at'    => \Carbon\Carbon::now(),
            ]);
        }


        // Create Manage Shout and its attributes
        Permission::create([
            'parent_id'     => null,
            'name'          => 'Manage Shout',
            'slug'          => 'manage-shout',
            'description'   => null,
        ]);
        $manageShout = Permission::where('slug', '=', 'manage-shout')->first();
        $manageShoutAttributes = [
            [
                'parent_id'     => $manageShout->id,
                'name'          => 'Manage Shout Create',
                'slug'          => 'manage-shout-create',
                'description'   => null,
            ],[
                'parent_id'     => $manageShout->id,
                'name'          => 'Manage Shout Delete',
                'slug'          => 'manage-shout-delete',
                'description'   => null,
            ],
        ];
        foreach ($manageShoutAttributes as $manageShoutAttribute) {
            Permission::create([
                'parent_id'     => $manageShoutAttribute['parent_id'],
                'name'          => $manageShoutAttribute['name'],
                'slug'          => $manageShoutAttribute['slug'],
                'description'   => $manageShoutAttribute['description'],
                'created_at'    => \Carbon\Carbon::now(),
                'updated_at'    => \Carbon\Carbon::now(),
            ]);
        }


        // Create Manage Forum and its attributes
        /*Permission::create([
            'parent_id'     => null,
            'name'          => 'Manage Forum',
            'slug'          => 'manage-forum',
            'description'   => null,
        ]);
        $manageForum = Permission::where('slug', '=', 'manage-forum')->first();
        $manageForumAttributes = [
            [
                'parent_id'     => $manageForum->id,
                'name'          => 'Manage Forum List',
                'slug'          => 'manage-forum-list',
                'description'   => null,
            ],[
                'parent_id'     => $manageForum->id,
                'name'          => 'Manage Forum Show',
                'slug'          => 'manage-forum-show',
                'description'   => null,
            ],[
                'parent_id'     => $manageForum->id,
                'name'          => 'Manage Forum Update',
                'slug'          => 'manage-forum-update',
                'description'   => null,
            ],[
                'parent_id'     => $manageForum->id,
                'name'          => 'Manage Forum Delete',
                'slug'          => 'manage-forum-delete',
                'description'   => null,
            ],
        ];
        foreach ($manageForumAttributes as $manageForumAttribute) {
            Permission::create([
                'parent_id'     => $manageForumAttribute['parent_id'],
                'name'          => $manageForumAttribute['name'],
                'slug'          => $manageForumAttribute['slug'],
                'description'   => $manageForumAttribute['description'],
                'created_at'    => \Carbon\Carbon::now(),
                'updated_at'    => \Carbon\Carbon::now(),
            ]);
        }*/


        // Create Manage Category and its attributes
        Permission::create([
            'parent_id'     => null,
            'name'          => 'Manage Category',
            'slug'          => 'manage-category',
            'description'   => null,
        ]);
        $manageCategory = Permission::where('slug', '=', 'manage-category')->first();
        $manageCategoryAttributes = [
            [
                'parent_id'     => $manageCategory->id,
                'name'          => 'Manage Category List',
                'slug'          => 'manage-category-list',
                'description'   => null,
            ],[
                'parent_id'     => $manageCategory->id,
                'name'          => 'Manage Category Create',
                'slug'          => 'manage-category-create',
                'description'   => null,
            ],[
                'parent_id'     => $manageCategory->id,
                'name'          => 'Manage Category Show',
                'slug'          => 'manage-category-show',
                'description'   => null,
            ],[
                'parent_id'     => $manageCategory->id,
                'name'          => 'Manage Category Update',
                'slug'          => 'manage-category-update',
                'description'   => null,
            ],[
                'parent_id'     => $manageCategory->id,
                'name'          => 'Manage Category Delete',
                'slug'          => 'manage-category-delete',
                'description'   => null,
            ],
        ];
        foreach ($manageCategoryAttributes as $manageCategoryAttribute) {
            Permission::create([
                'parent_id'     => $manageCategoryAttribute['parent_id'],
                'name'          => $manageCategoryAttribute['name'],
                'slug'          => $manageCategoryAttribute['slug'],
                'description'   => $manageCategoryAttribute['description'],
                'created_at'    => \Carbon\Carbon::now(),
                'updated_at'    => \Carbon\Carbon::now(),
            ]);
        }


        // Create Manage Upload and its attributes
        Permission::create([
            'parent_id'     => null,
            'name'          => 'Manage Upload',
            'slug'          => 'manage-upload',
            'description'   => null,
        ]);
        $manageUpload = Permission::where('slug', '=', 'manage-upload')->first();
        $manageUploadAttributes = [
            [
                'parent_id'     => $manageUpload->id,
                'name'          => 'Manage Upload List',
                'slug'          => 'manage-upload-list',
                'description'   => null,
            ],[
                'parent_id'     => $manageUpload->id,
                'name'          => 'Manage Upload Create',
                'slug'          => 'manage-upload-create',
                'description'   => null,
            ],[
                'parent_id'     => $manageUpload->id,
                'name'          => 'Manage Upload Show',
                'slug'          => 'manage-upload-show',
                'description'   => null,
            ],[
                'parent_id'     => $manageUpload->id,
                'name'          => 'Manage Upload Update',
                'slug'          => 'manage-upload-update',
                'description'   => null,
            ],[
                'parent_id'     => $manageUpload->id,
                'name'          => 'Manage Upload Delete',
                'slug'          => 'manage-upload-delete',
                'description'   => null,
            ],
        ];
        foreach ($manageUploadAttributes as $manageUploadAttribute) {
            Permission::create([
                'parent_id'     => $manageUploadAttribute['parent_id'],
                'name'          => $manageUploadAttribute['name'],
                'slug'          => $manageUploadAttribute['slug'],
                'description'   => $manageUploadAttribute['description'],
                'created_at'    => \Carbon\Carbon::now(),
                'updated_at'    => \Carbon\Carbon::now(),
            ]);
        }


        // Create Manage MyUpload and its attributes
        Permission::create([
            'parent_id'     => null,
            'name'          => 'Manage MyUpload',
            'slug'          => 'manage-myupload',
            'description'   => null,
        ]);
        $manageMyUpload = Permission::where('slug', '=', 'manage-myupload')->first();
        $manageMyUploadAttributes = [
            [
                'parent_id'     => $manageMyUpload->id,
                'name'          => 'Manage MyUpload List',
                'slug'          => 'manage-myupload-list',
                'description'   => null,
            ],[
                'parent_id'     => $manageMyUpload->id,
                'name'          => 'Manage MyUpload Create',
                'slug'          => 'manage-myupload-create',
                'description'   => null,
            ],[
                'parent_id'     => $manageMyUpload->id,
                'name'          => 'Manage MyUpload Show',
                'slug'          => 'manage-myupload-show',
                'description'   => null,
            ],[
                'parent_id'     => $manageMyUpload->id,
                'name'          => 'Manage MyUpload Update',
                'slug'          => 'manage-myupload-update',
                'description'   => null,
            ],[
                'parent_id'     => $manageMyUpload->id,
                'name'          => 'Manage MyUpload Delete',
                'slug'          => 'manage-myupload-delete',
                'description'   => null,
            ],
        ];
        foreach ($manageMyUploadAttributes as $manageMyUploadAttribute) {
            Permission::create([
                'parent_id'     => $manageMyUploadAttribute['parent_id'],
                'name'          => $manageMyUploadAttribute['name'],
                'slug'          => $manageMyUploadAttribute['slug'],
                'description'   => $manageMyUploadAttribute['description'],
                'created_at'    => \Carbon\Carbon::now(),
                'updated_at'    => \Carbon\Carbon::now(),
            ]);
        }


        // Create Manage Pin and its attributes
        Permission::create([
            'parent_id'     => null,
            'name'          => 'Manage Pin',
            'slug'          => 'manage-pin',
            'description'   => null,
        ]);
        $managePin = Permission::where('slug', '=', 'manage-pin')->first();
        $managePinAttributes = [
            [
                'parent_id'     => $managePin->id,
                'name'          => 'Manage Pin List',
                'slug'          => 'manage-pin-list',
                'description'   => null,
            ],[
                'parent_id'     => $managePin->id,
                'name'          => 'Manage Pin Create',
                'slug'          => 'manage-pin-create',
                'description'   => null,
            ],[
                'parent_id'     => $managePin->id,
                'name'          => 'Manage Pin Delete',
                'slug'          => 'manage-pin-delete',
                'description'   => null,
            ],
        ];
        foreach ($managePinAttributes as $managePinAttribute) {
            Permission::create([
                'parent_id'     => $managePinAttribute['parent_id'],
                'name'          => $managePinAttribute['name'],
                'slug'          => $managePinAttribute['slug'],
                'description'   => $managePinAttribute['description'],
                'created_at'    => \Carbon\Carbon::now(),
                'updated_at'    => \Carbon\Carbon::now(),
            ]);
        }


        // Create Manage Recommend and its attributes
        Permission::create([
            'parent_id'     => null,
            'name'          => 'Manage Recommend',
            'slug'          => 'manage-recommend',
            'description'   => null,
        ]);
        $manageRecommend = Permission::where('slug', '=', 'manage-recommend')->first();
        $manageRecommendAttributes = [
            [
                'parent_id'     => $manageRecommend->id,
                'name'          => 'Manage Recommend List',
                'slug'          => 'manage-recommend-list',
                'description'   => null,
            ],[
                'parent_id'     => $manageRecommend->id,
                'name'          => 'Manage Recommend Create',
                'slug'          => 'manage-recommend-create',
                'description'   => null,
            ],[
                'parent_id'     => $manageRecommend->id,
                'name'          => 'Manage Recommend Delete',
                'slug'          => 'manage-recommend-delete',
                'description'   => null,
            ],
        ];
        foreach ($manageRecommendAttributes as $manageRecommendAttribute) {
            Permission::create([
                'parent_id'     => $manageRecommendAttribute['parent_id'],
                'name'          => $manageRecommendAttribute['name'],
                'slug'          => $manageRecommendAttribute['slug'],
                'description'   => $manageRecommendAttribute['description'],
                'created_at'    => \Carbon\Carbon::now(),
                'updated_at'    => \Carbon\Carbon::now(),
            ]);
        }


        // Create Manage Upcoming and its attributes
        Permission::create([
            'parent_id'     => null,
            'name'          => 'Manage Upcoming',
            'slug'          => 'manage-upcoming',
            'description'   => null,
        ]);
        $manageUpcoming = Permission::where('slug', '=', 'manage-upcoming')->first();
        $manageUpcomingAttributes = [
            [
                'parent_id'     => $manageUpcoming->id,
                'name'          => 'Manage Upcoming List',
                'slug'          => 'manage-upcoming-list',
                'description'   => null,
            ],[
                'parent_id'     => $manageUpcoming->id,
                'name'          => 'Manage Upcoming Create',
                'slug'          => 'manage-upcoming-create',
                'description'   => null,
            ],[
                'parent_id'     => $manageUpcoming->id,
                'name'          => 'Manage Upcoming Show',
                'slug'          => 'manage-upcoming-show',
                'description'   => null,
            ],[
                'parent_id'     => $manageUpcoming->id,
                'name'          => 'Manage Upcoming Update',
                'slug'          => 'manage-upcoming-update',
                'description'   => null,
            ],[
                'parent_id'     => $manageUpcoming->id,
                'name'          => 'Manage Upcoming Delete',
                'slug'          => 'manage-upcoming-delete',
                'description'   => null,
            ],
        ];
        foreach ($manageUpcomingAttributes as $manageUpcomingAttribute) {
            Permission::create([
                'parent_id'     => $manageUpcomingAttribute['parent_id'],
                'name'          => $manageUpcomingAttribute['name'],
                'slug'          => $manageUpcomingAttribute['slug'],
                'description'   => $manageUpcomingAttribute['description'],
                'created_at'    => \Carbon\Carbon::now(),
                'updated_at'    => \Carbon\Carbon::now(),
            ]);
        }


        // Create NavCategory and its attributes
        Permission::create([
            'parent_id'     => null,
            'name'          => 'NavCategory',
            'slug'          => 'navcategory',
            'description'   => null,
        ]);
        $NavCategory = Permission::where('slug', '=', 'navcategory')->first();
        Permission::create([
            'parent_id'     => $NavCategory->id,
            'name'          => 'NavCategory AutoAssign',
            'slug'          => 'navcategory-autoassign',
            'description'   => null,
        ]);
        // NavCategory Attributes must be created when creating the category
        // and must be assigned to the dev, admin at once...


        // Create Upload Approval and its attributes
        Permission::create([
            'parent_id'     => null,
            'name'          => 'Upload Approval',
            'slug'          => 'upload-approval',
            'description'   => null,
        ]);
        $UploadApproval = Permission::where('slug', '=', 'upload-approval')->first();
        $UploadApprovalAttributes = [
            [
                'parent_id'     => $UploadApproval->id,
                'name'          => 'Upload Approval Update',
                'slug'          => 'upload-approval-update',
                'description'   => null,
            ],[
                'parent_id'     => $UploadApproval->id,
                'name'          => 'Upload Approval Approve',
                'slug'          => 'upload-approval-approve',
                'description'   => null,
            ],[
                'parent_id'     => $UploadApproval->id,
                'name'          => 'Upload Approval Disapprove',
                'slug'          => 'upload-approval-disapprove',
                'description'   => null,
            ],[
                'parent_id'     => $UploadApproval->id,
                'name'          => 'Upload Approval Pending',
                'slug'          => 'upload-approval-pending',
                'description'   => null,
            ],
        ];
        foreach ($UploadApprovalAttributes as $UploadApprovalAttribute) {
            Permission::create([
                'parent_id'     => $UploadApprovalAttribute['parent_id'],
                'name'          => $UploadApprovalAttribute['name'],
                'slug'          => $UploadApprovalAttribute['slug'],
                'description'   => $UploadApprovalAttribute['description'],
                'created_at'    => \Carbon\Carbon::now(),
                'updated_at'    => \Carbon\Carbon::now(),
            ]);
        }

        $role = Role::where('slug','=','developer')->first();
        $permissions = Permission::all();
        foreach ($permissions as $permission)
        {
            $role->permissions()->attach($permission->id);
        }
    }
}
