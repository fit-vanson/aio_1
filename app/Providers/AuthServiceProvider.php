<?php

namespace App\Providers;

use App\Http\Controllers\KhosimController;
use App\Policies\CocsimPolicy;
use App\Policies\DaPolicy;
use App\Policies\DevAmazonPolicy;
use App\Policies\DevHuaweiPolicy;
use App\Policies\DevicePolicy;
use App\Policies\DevOppoPolicy;
use App\Policies\DevPolicy;
use App\Policies\DevSamsungPolicy;
use App\Policies\DevVivoPolicy;
use App\Policies\DevXiaomiPolicy;
use App\Policies\Ga_devPolicy;
use App\Policies\GaPolicy;
use App\Policies\HubPolicy;
use App\Policies\KeystorePolicy;
use App\Policies\MailParentPolicy;
use App\Policies\MailRegPolicy;
use App\Policies\MaiManagePolicy;
use App\Policies\PermissionPolicy;
use App\Policies\ProjectPolicy;
use App\Policies\RolePolicy;
use App\Policies\ScriptPolicy;
use App\Policies\SmsPolicy;
use App\Policies\TemplatePolicy;
use App\Policies\TemplatePreviewPolicy;
use App\Policies\UserPolicy;
use App\Policies\KhosimPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use function PHPUnit\Framework\callback;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

       $this->defineProject();
       $this->defineDu_an();
       $this->defineTemplate();
       $this->defineTemplatePreview();
       $this->defineKeystore();
       $this->defineGadev();
       $this->defineGa();
       $this->defineDev();
       $this->defineDevAmazon();
       $this->defineDevSamsung();
       $this->defineDevXiaomi();
       $this->defineDevOppo();
       $this->defineDevVivo();
       $this->defineDevHuawei();
       $this->defineUser();
       $this->defineVaitro();
       $this->definePhan_quyen();
       $this->defineKhosim();
       $this->defineCocsim();
       $this->defineHub();
       $this->defineSms();
       $this->defineMailManage();
       $this->defineMailParent();
       $this->defineMailReg();
       $this->defineDevice();
       $this->defineScript();
    }

    public function defineProject(){

        Gate::define('project-index', [ProjectPolicy::class, 'index']);
        Gate::define('project-show', [ProjectPolicy::class, 'show']);
        Gate::define('project-add', [ProjectPolicy::class, 'add']);
        Gate::define('project-edit', [ProjectPolicy::class, 'edit']);
        Gate::define('project-update', [ProjectPolicy::class, 'update']);
        Gate::define('project-delete', [ProjectPolicy::class, 'delete']);

    }
    public function defineDu_an(){
        Gate::define('du_an-index', [DaPolicy::class, 'index']);
        Gate::define('du_an-show', [DaPolicy::class, 'show']);
        Gate::define('du_an-add', [DaPolicy::class, 'add']);
        Gate::define('du_an-edit', [DaPolicy::class, 'edit']);
        Gate::define('du_an-update', [DaPolicy::class, 'update']);
        Gate::define('du_an-delete', [DaPolicy::class, 'delete']);
    }
    public function defineTemplate(){
        Gate::define('template-index', [TemplatePolicy::class, 'index']);
        Gate::define('template-show', [TemplatePolicy::class, 'show']);
        Gate::define('template-add', [TemplatePolicy::class, 'add']);
        Gate::define('template-edit', [TemplatePolicy::class, 'edit']);
        Gate::define('template-update', [TemplatePolicy::class, 'update']);
        Gate::define('template-delete', [TemplatePolicy::class, 'delete']);
    }

    public function defineTemplatePreview(){
        Gate::define('template-preview-index', [TemplatePreviewPolicy::class, 'index']);
        Gate::define('template-preview-show', [TemplatePreviewPolicy::class, 'show']);
        Gate::define('template-preview-add', [TemplatePreviewPolicy::class, 'add']);
        Gate::define('template-preview-edit', [TemplatePreviewPolicy::class, 'edit']);
        Gate::define('template-preview-update', [TemplatePreviewPolicy::class, 'update']);
        Gate::define('template-preview-delete', [TemplatePreviewPolicy::class, 'delete']);
    }

    public function defineKeystore(){
        Gate::define('keystore-index', [KeystorePolicy::class, 'index']);
        Gate::define('keystore-show', [KeystorePolicy::class, 'show']);
        Gate::define('keystore-add', [KeystorePolicy::class, 'add']);
        Gate::define('keystore-edit', [KeystorePolicy::class, 'edit']);
        Gate::define('keystore-update', [KeystorePolicy::class, 'update']);
        Gate::define('keystore-delete', [KeystorePolicy::class, 'delete']);
    }
    public function defineGadev(){
        Gate::define('gadev-index', [Ga_devPolicy::class, 'index']);
        Gate::define('gadev-show', [Ga_devPolicy::class, 'show']);
        Gate::define('gadev-add', [Ga_devPolicy::class, 'add']);
        Gate::define('gadev-edit', [Ga_devPolicy::class, 'edit']);
        Gate::define('gadev-update', [Ga_devPolicy::class, 'update']);
        Gate::define('gadev-delete', [Ga_devPolicy::class, 'delete']);
    }
    public function defineGa(){
        Gate::define('ga-index', [GaPolicy::class, 'index']);
        Gate::define('ga-show', [GaPolicy::class, 'show']);
        Gate::define('ga-add', [GaPolicy::class, 'add']);
        Gate::define('ga-edit', [GaPolicy::class, 'edit']);
        Gate::define('ga-update', [GaPolicy::class, 'update']);
        Gate::define('ga-delete', [GaPolicy::class, 'delete']);
    }

    public function defineDev(){

        Gate::define('dev-index', [DevPolicy::class, 'index']);
        Gate::define('dev-show', [DevPolicy::class, 'show']);
        Gate::define('dev-add', [DevPolicy::class, 'add']);
        Gate::define('dev-edit', [DevPolicy::class, 'edit']);
        Gate::define('dev-update', [DevPolicy::class, 'update']);
        Gate::define('dev-delete', [DevPolicy::class, 'delete']);
    }

    public function defineDevAmazon(){

        Gate::define('dev_amazon-index', [DevAmazonPolicy::class, 'index']);
        Gate::define('dev_amazon-show', [DevAmazonPolicy::class, 'show']);
        Gate::define('dev_amazon-add', [DevAmazonPolicy::class, 'add']);
        Gate::define('dev_amazon-edit', [DevAmazonPolicy::class, 'edit']);
        Gate::define('dev_amazon-update', [DevAmazonPolicy::class, 'update']);
        Gate::define('dev_amazon-delete', [DevAmazonPolicy::class, 'delete']);
    }


    public function defineDevSamsung(){

        Gate::define('dev_samsung-index', [DevSamsungPolicy::class, 'index']);
        Gate::define('dev_samsung-show', [DevSamsungPolicy::class, 'show']);
        Gate::define('dev_samsung-add', [DevSamsungPolicy::class, 'add']);
        Gate::define('dev_samsung-edit', [DevSamsungPolicy::class, 'edit']);
        Gate::define('dev_samsung-update', [DevSamsungPolicy::class, 'update']);
        Gate::define('dev_samsung-delete', [DevSamsungPolicy::class, 'delete']);
    }


    public function defineDevXiaomi(){

        Gate::define('dev_xiaomi-index', [DevXiaomiPolicy::class, 'index']);
        Gate::define('dev_xiaomi-show', [DevXiaomiPolicy::class, 'show']);
        Gate::define('dev_xiaomi-add', [DevXiaomiPolicy::class, 'add']);
        Gate::define('dev_xiaomi-edit', [DevXiaomiPolicy::class, 'edit']);
        Gate::define('dev_xiaomi-update', [DevXiaomiPolicy::class, 'update']);
        Gate::define('dev_xiaomi-delete', [DevXiaomiPolicy::class, 'delete']);
    }


    public function defineDevOppo(){

        Gate::define('dev_oppo-index', [DevOppoPolicy::class, 'index']);
        Gate::define('dev_oppo-show', [DevOppoPolicy::class, 'show']);
        Gate::define('dev_oppo-add', [DevOppoPolicy::class, 'add']);
        Gate::define('dev_oppo-edit', [DevOppoPolicy::class, 'edit']);
        Gate::define('dev_oppo-update', [DevOppoPolicy::class, 'update']);
        Gate::define('dev_oppo-delete', [DevOppoPolicy::class, 'delete']);
    }


    public function defineDevVivo(){

        Gate::define('dev_vivo-index', [DevVivoPolicy::class, 'index']);
        Gate::define('dev_vivo-show', [DevVivoPolicy::class, 'show']);
        Gate::define('dev_vivo-add', [DevVivoPolicy::class, 'add']);
        Gate::define('dev_vivo-edit', [DevVivoPolicy::class, 'edit']);
        Gate::define('dev_vivo-update', [DevVivoPolicy::class, 'update']);
        Gate::define('dev_vivo-delete', [DevVivoPolicy::class, 'delete']);
    }

    public function defineDevHuawei(){

        Gate::define('dev_huawei-index', [DevHuaweiPolicy::class, 'index']);
        Gate::define('dev_huawei-show', [DevHuaweiPolicy::class, 'show']);
        Gate::define('dev_huawei-add', [DevHuaweiPolicy::class, 'add']);
        Gate::define('dev_huawei-edit', [DevHuaweiPolicy::class, 'edit']);
        Gate::define('dev_huawei-update', [DevHuaweiPolicy::class, 'update']);
        Gate::define('dev_huawei-delete', [DevHuaweiPolicy::class, 'delete']);
    }


    public function defineVaitro(){
        Gate::define('vai_tro-index', [RolePolicy::class, 'index']);
        Gate::define('vai_tro-show', [RolePolicy::class, 'show']);
        Gate::define('vai_tro-add', [RolePolicy::class, 'add']);
        Gate::define('vai_tro-edit', [RolePolicy::class, 'edit']);
        Gate::define('vai_tro-update', [RolePolicy::class, 'update']);
        Gate::define('vai_tro-delete', [RolePolicy::class, 'delete']);
    }
    public function defineUser(){
        Gate::define('user-index', [UserPolicy::class, 'index']);
        Gate::define('user-show', [UserPolicy::class, 'show']);
        Gate::define('user-add', [UserPolicy::class, 'add']);
        Gate::define('user-edit', [UserPolicy::class, 'edit']);
        Gate::define('user-update', [UserPolicy::class, 'update']);
        Gate::define('user-delete', [UserPolicy::class, 'delete']);
    }
    public function definePhan_quyen(){
        Gate::define('phan_quyen-index', [PermissionPolicy::class, 'index']);
        Gate::define('phan_quyen-show', [PermissionPolicy::class, 'show']);
        Gate::define('phan_quyen-add', [PermissionPolicy::class, 'add']);
        Gate::define('phan_quyen-edit', [PermissionPolicy::class, 'edit']);
        Gate::define('phan_quyen-update', [PermissionPolicy::class, 'update']);
        Gate::define('phan_quyen-delete', [PermissionPolicy::class, 'delete']);

    }

    public function defineKhosim(){
        Gate::define('khosim-index', [KhosimPolicy::class, 'index']);
        Gate::define('khosim-show', [KhosimPolicy::class, 'show']);
        Gate::define('khosim-add', [KhosimPolicy::class, 'add']);
        Gate::define('khosim-edit', [KhosimPolicy::class, 'edit']);
        Gate::define('khosim-update', [KhosimPolicy::class, 'update']);
        Gate::define('khosim-delete', [KhosimPolicy::class, 'delete']);

    }
    public function defineCocsim(){
        Gate::define('cocsim-index', [CocsimPolicy::class, 'index']);
        Gate::define('cocsim-show', [CocsimPolicy::class, 'show']);
        Gate::define('cocsim-add', [CocsimPolicy::class, 'add']);
        Gate::define('cocsim-edit', [CocsimPolicy::class, 'edit']);
        Gate::define('cocsim-update', [CocsimPolicy::class, 'update']);
        Gate::define('cocsim-delete', [CocsimPolicy::class, 'delete']);

    }
    public function defineHub(){
        Gate::define('hub-index', [HubPolicy::class, 'index']);
        Gate::define('hub-show', [HubPolicy::class, 'show']);
        Gate::define('hub-add', [HubPolicy::class, 'add']);
        Gate::define('hub-edit', [HubPolicy::class, 'edit']);
        Gate::define('hub-update', [HubPolicy::class, 'update']);
        Gate::define('hub-delete', [HubPolicy::class, 'delete']);
    }
    public function defineSms(){
        Gate::define('sms-index', [SmsPolicy::class, 'index']);
        Gate::define('sms-show', [SmsPolicy::class, 'show']);
        Gate::define('sms-add', [SmsPolicy::class, 'add']);
        Gate::define('sms-edit', [SmsPolicy::class, 'edit']);
        Gate::define('sms-update', [SmsPolicy::class, 'update']);
        Gate::define('sms-delete', [SmsPolicy::class, 'delete']);
    }

    public function defineMailManage(){
        Gate::define('mail_manage-index', [MaiManagePolicy::class, 'index']);
        Gate::define('mail_manage-show', [MaiManagePolicy::class, 'show']);
        Gate::define('mail_manage-add', [MaiManagePolicy::class, 'add']);
        Gate::define('mail_manage-edit', [MaiManagePolicy::class, 'edit']);
        Gate::define('mail_manage-update', [MaiManagePolicy::class, 'update']);
        Gate::define('mail_manage-delete', [MaiManagePolicy::class, 'delete']);
    }

    public function defineMailParent(){
        Gate::define('mail_parent-index', [MailParentPolicy::class, 'index']);
        Gate::define('mail_parent-show', [MailParentPolicy::class, 'show']);
        Gate::define('mail_parent-add', [MailParentPolicy::class, 'add']);
        Gate::define('mail_parent-edit', [MailParentPolicy::class, 'edit']);
        Gate::define('mail_parent-update', [MailParentPolicy::class, 'update']);
        Gate::define('mail_parent-delete', [MailParentPolicy::class, 'delete']);
    }

    public function defineMailReg(){
        Gate::define('mail_reg-index', [MailRegPolicy::class, 'index']);
        Gate::define('mail_reg-show', [MailRegPolicy::class, 'show']);
        Gate::define('mail_reg-add', [MailRegPolicy::class, 'add']);
        Gate::define('mail_reg-edit', [MailRegPolicy::class, 'edit']);
        Gate::define('mail_reg-update', [MailRegPolicy::class, 'update']);
        Gate::define('mail_reg-delete', [MailRegPolicy::class, 'delete']);
    }


    public function defineDevice(){
        Gate::define('device-index', [DevicePolicy::class, 'index']);
        Gate::define('device-show', [DevicePolicy::class, 'show']);
        Gate::define('device-add', [DevicePolicy::class, 'add']);
        Gate::define('device-edit', [DevicePolicy::class, 'edit']);
        Gate::define('device-update', [DevicePolicy::class, 'update']);
        Gate::define('device-delete', [DevicePolicy::class, 'delete']);
    }

    public function defineScript(){
        Gate::define('script-index', [ScriptPolicy::class, 'index']);
        Gate::define('script-show', [ScriptPolicy::class, 'show']);
        Gate::define('script-add', [ScriptPolicy::class, 'add']);
        Gate::define('script-edit', [ScriptPolicy::class, 'edit']);
        Gate::define('script-update', [ScriptPolicy::class, 'update']);
        Gate::define('script-delete', [ScriptPolicy::class, 'delete']);
    }



}
