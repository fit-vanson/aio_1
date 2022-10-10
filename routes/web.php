<?php

use App\Http\Controllers\Apk_ProcessController;
use App\Http\Controllers\BotController;
use App\Http\Controllers\BuildPreviewController;
use App\Http\Controllers\CategoryTemplateController;
use App\Http\Controllers\CategoryTemplateFrameController;
use App\Http\Controllers\CheckApiController;
use App\Http\Controllers\CocsimController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\CronProjectController;
use App\Http\Controllers\DaController;
use App\Http\Controllers\DataProfileController;
use App\Http\Controllers\DesignContentController;
use App\Http\Controllers\DesignController;
use App\Http\Controllers\DevAmazonController;
use App\Http\Controllers\DevController;

use App\Http\Controllers\DevHuaweiController;
use App\Http\Controllers\DeviceInfoController;
use App\Http\Controllers\DevOppoController;
use App\Http\Controllers\DevSamsungController;
use App\Http\Controllers\DevVivoController;
use App\Http\Controllers\DevXiaomiController;
use App\Http\Controllers\Ga_devController;
use App\Http\Controllers\GaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HubController;
use App\Http\Controllers\ImeiController;
use App\Http\Controllers\ipInfoController;
use App\Http\Controllers\KeystoreController;
use App\Http\Controllers\KhosimController;
use App\Http\Controllers\MailManageController;
use App\Http\Controllers\MailParentController;
use App\Http\Controllers\MailRegController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Project_Controller;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ScriptController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\TemplatePreviewController;
use App\Http\Controllers\TemplateTextPrController;
use App\Http\Controllers\TwoFaceAuthsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerifyTwoFaceController;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/clear',function (){
    echo  Artisan::call('optimize:clear');
    echo  Artisan::call('cache:clear');
    echo  Artisan::call('config:cache');
    echo  Artisan::call('route:cache');
});
Route::get('/link',function (){
    echo  Artisan::call('storage:link');
});
Route::get('/job',function (){
    return  Artisan::call('queue:work  --tries=3 --timeout=60');
});

Route::get('/phpinfo',function (){
    echo phpinfo();
});

Route::get('routes', function () {
    $routeCollection = Route::getRoutes();

    echo "<table style='width:100%'>";
    echo "<tr>";
    echo "<td width='10%'><h4>HTTP Method</h4></td>";
    echo "<td width='10%'><h4>Route</h4></td>";
    echo "<td width='10%'><h4>Name</h4></td>";
    echo "<td width='70%'><h4>Corresponding Action</h4></td>";
    echo "</tr>";
    foreach ($routeCollection as $value) {
        echo "<tr>";
        echo "<td>" . $value->methods()[0] . "</td>";
        echo "<td>" . $value->uri() . "</td>";
        echo "<td>" . $value->getName() . "</td>";
        echo "<td>" . $value->getActionName() . "</td>";
        echo "</tr>";
    }
    echo count($routeCollection);
    echo "</table>";
});


Route::get('/updateapp', function()
{
//    composer dump-autoload
    return  "<pre>". shell_exec ('composer dump-autoload')."</pre>";
//    echo 'dump-autoload complete';
});


Route::group(['prefix'=>'fake111'], function (){
    Route::get('/',[HomeController::class,'fake']);

});
Route::group(['prefix'=>'login','middleware'=>'CheckUser'], function (){
    Route::get('/',[HomeController::class,'getLogin']);
    Route::post('/',[HomeController::class,'postLogin'])->name('login');
});
Route::get('logout',[HomeController::class,'logout'])->name('logout');
Route::group(['prefix'=>'admin','middleware'=>['CheckLogout','2fa']], function (){
    Route::get('/',[HomeController::class,'getHome'])->name('index');
});

Route::get('/',[HomeController::class,'getHome'])->middleware(['CheckLogout','2fa'])->name('index');

Route::group(['prefix'=>'settings','middleware'=>['CheckLogout','2fa']], function (){
    Route::get('/',[SettingController::class,'index'])->name('settings.index');
    Route::post('/update',[SettingController::class,'update'])->name('settings.update');
    Route::post('/clear-log',[SettingController::class,'clear_logs'])->name('settings.clear_logs');

});

Route::group(['prefix'=>'user','middleware'=>['CheckLogout','2fa']], function (){
    Route::get('/',[UserController::class,'index'])->name('user.index')->middleware('can:user-index');
    Route::post('/create',[UserController::class,'create'])->name('user.create')->middleware('can:user-add');
    Route::get('/edit/{id}',[UserController::class,'edit'])->name('user.edit')->middleware('can:user-edit');
    Route::get('/show/{id}',[UserController::class,'show'])->name('user.show')->middleware('can:user-show');
    Route::post('/update',[UserController::class,'update'])->name('user.update')->middleware('can:user-update');
    Route::get('/delete/{id}',[UserController::class,'delete'])->name('user.delete')->middleware('can:user-delete');
    Route::get('/reset2fa/{id}',[UserController::class,'reset2fa'])->name('user.reset2fa')->middleware('can:user-edit');
});
Route::group(['prefix'=>'role','middleware'=>['CheckLogout','2fa']], function (){
    Route::get('/',[RoleController::class,'index'])->name('role.index');
    Route::post('/create',[RoleController::class,'create'])->name('role.create');
    Route::get('/edit/{id}',[RoleController::class,'edit'])->name('role.edit');
    Route::get('/show/{id}',[RoleController::class,'show'])->name('role.show');
    Route::post('/update',[RoleController::class,'update'])->name('role.update');
    Route::get('/delete/{id}',[RoleController::class,'delete'])->name('role.delete');
});
Route::group(['prefix'=>'permission','middleware'=>['CheckLogout','2fa']], function (){
    Route::get('/',[PermissionController::class,'index'])->name('permission.index')->middleware('can:phan_quyen-index');
    Route::post('/create',[PermissionController::class,'create'])->name('permission.create')->middleware('can:phan_quyen-add');
    Route::get('/edit/{id}',[PermissionController::class,'edit'])->name('permission.edit')->middleware('can:phan_quyen-edit');
    Route::get('/show/{id}',[PermissionController::class,'show'])->name('permission.show')->middleware('can:phan_quyen-show');
    Route::post('/update',[PermissionController::class,'update'])->name('permission.update')->middleware('can:phan_quyen-update');
    Route::get('/delete/{id}',[PermissionController::class,'delete'])->name('permission.delete')->middleware('can:phan_quyen-delete');
});


Route::get('/fakeimage',[ProjectController::class,'getInfofake'])->name('project.getInfofake')->middleware('can:project-index');

//Route::group(['prefix'=>'project2','middleware'=>['CheckLogout','2fa']], function (){
//
////    Route::get('/get',[ProjectController2::class,'getProject']);
//    Route::get('/',[ProjectController::class,'index'])->name('project.index')->middleware('can:project-index');
//    Route::get('/indexBuild',[ProjectController::class,'indexBuild'])->name('project.indexBuild')->middleware('can:project-index');
//    Route::post('/getIndex',[ProjectController::class,'getIndex'])->name('project.getIndex')->middleware('can:project-index');
//    Route::post('/getIndexBuild',[ProjectController::class,'getIndexBuild'])->name('project.getIndexBuild')->middleware('can:project-index');
//    Route::post('/create',[ProjectController::class,'create'])->name('project.create')->middleware('can:project-add');
//    Route::get('/edit/{id}',[ProjectController::class,'edit'])->name('project.edit')->middleware('can:project-edit');
//    Route::get('/fake/{id}',[ProjectController::class,'fake'])->name('project.fake')->middleware('can:project-index');
//
//
//
//    Route::get('/editDes_EN/{id}',[ProjectController::class,'editDesEN'])->name('project.editDesEN')->middleware('can:project-edit');
//    Route::get('/editDes_VN/{id}',[ProjectController::class,'editDesVN'])->name('project.editDesVN')->middleware('can:project-edit');
//
//
//    Route::get('/show/{id}',[ProjectController::class,'show'])->name('project.show');
//    Route::post('/update',[ProjectController::class,'update'])->name('project.update')->middleware('can:project-update');
//    Route::post('/updateQuick',[ProjectController::class,'updateQuick'])->name('project.updateQuick')->middleware('can:project-update');
//    Route::post('/updatePart',[ProjectController::class,'updatePart'])->name('project.updatePart')->middleware('can:project-update');
//    Route::post('/updateDesEN',[ProjectController::class,'updateDesEN'])->name('project.updateDesEN')->middleware('can:project-update');
//    Route::post('/updateDesVN',[ProjectController::class,'updateDesVN'])->name('project.updateDesVN')->middleware('can:project-update');
//
//    Route::post('/updateStatus',[ProjectController::class,'updateStatus'])->name('project.updateStatus')->middleware('can:project-update');
//    Route::get('/delete/{id}',[ProjectController::class,'delete'])->name('project.delete')->middleware('can:project-delete');
//    Route::get('/removeProject/{id}',[ProjectController::class,'removeProject'])->name('project.removeProject')->middleware('can:project-edit');
//
//    Route::get('/checkData/{id}',[ProjectController::class,'checkData'])->name('project.checkData')->middleware('can:project-edit');
//
//    Route::get('/appChplay',[ProjectController::class,'appChplay'])->name('project.appChplay')->middleware('can:project-index');
//    Route::post('/getChplay',[ProjectController::class,'getChplay'])->name('project.getChplay')->middleware('can:project-index');
//    Route::get('/checkbox/{id}',[ProjectController::class,'checkbox'])->name('project.checkbox')->middleware('can:project-edit');
//
//    Route::get('/appAmazon',[ProjectController::class,'appAmazon'])->name('project.appAmazon')->middleware('can:project-index');
//    Route::post('/getAmazon',[ProjectController::class,'getAmazon'])->name('project.getAmazon')->middleware('can:project-index');
//
//    Route::get('/appSamsung',[ProjectController::class,'appSamsung'])->name('project.appSamsung')->middleware('can:project-index');
//    Route::post('/getSamsung',[ProjectController::class,'getSamsung'])->name('project.getSamsung')->middleware('can:project-index');
//
//    Route::get('/appXiaomi',[ProjectController::class,'appXiaomi'])->name('project.appXiaomi')->middleware('can:project-index');
//    Route::post('/getXiaomi',[ProjectController::class,'getXiaomi'])->name('project.getXiaomi')->middleware('can:project-index');
//
//    Route::get('/appOppo',[ProjectController::class,'appOppo'])->name('project.appOppo')->middleware('can:project-index');
//    Route::post('/getOppo',[ProjectController::class,'getOppo'])->name('project.getOppo')->middleware('can:project-index');
//
//    Route::get('/appVivo',[ProjectController::class,'appVivo'])->name('project.appVivo')->middleware('can:project-index');
//    Route::post('/getVivo',[ProjectController::class,'getVivo'])->name('project.getVivo')->middleware('can:project-index');
//
//    Route::get('/appHuawei',[ProjectController::class,'appHuawei'])->name('project.appHuawei')->middleware('can:project-index');
//    Route::post('/getHuawei',[ProjectController::class,'getHuawei'])->name('project.getHuawei')->middleware('can:project-index');
//
//    Route::post('/select-template',[ProjectController::class,'select_template']);
//    Route::post('/select-store-name-chplay',[ProjectController::class,'select_store_name_chplay'])->name('select_store_name_chplay');
//    Route::post('/select-store-name-amazon',[ProjectController::class,'select_store_name_amazon'])->name('select_store_name_amazon');
//    Route::post('/select-store-name-samsung',[ProjectController::class,'select_store_name_samsung'])->name('select_store_name_samsung');
//    Route::post('/select-store-name-xiaomi',[ProjectController::class,'select_store_name_xiaomi'])->name('select_store_name_xiaomi');
//    Route::post('/select-store-name-oppo',[ProjectController::class,'select_store_name_oppo'])->name('select_store_name_oppo');
//    Route::post('/select-store-name-vivo',[ProjectController::class,'select_store_name_vivo'])->name('select_store_name_vivo');
//    Route::post('/select-store-name-huawei',[ProjectController::class,'select_store_name_huawei'])->name('select_store_name_huawei');
//
//    Route::post('/select-buildinfo_keystore',[ProjectController::class,'select_buildinfo_keystore']);
////    Route::post('/select-chplay_buildinfo_keystore',[ProjectController::class,'select_chplay_buildinfo_keystore']);
////    Route::post('/select-amazon_buildinfo_keystore',[ProjectController::class,'select_amazon_buildinfo_keystore']);
////    Route::post('/select-samsung_buildinfo_keystore',[ProjectController::class,'select_samsung_buildinfo_keystore']);
////    Route::post('/select-xiaomi_buildinfo_keystore',[ProjectController::class,'select_xiaomi_buildinfo_keystore']);
////    Route::post('/select-oppo_buildinfo_keystore',[ProjectController::class,'select_oppo_buildinfo_keystore']);
////    Route::post('/select-vivo_buildinfo_keystore',[ProjectController::class,'select_vivo_buildinfo_keystore']);
////    Route::post('/select-huawei_buildinfo_keystore',[ProjectController::class,'select_huawei_buildinfo_keystore']);
//
//
//
//    Route::get('/showlog/{id}',[ProjectController::class,'showlog'])->name('project.showlog')->middleware('can:project-index');
//    Route::get('/check_build',[ProjectController::class,'check_build'])->name('project.check_build')->middleware('can:project-index');
//    Route::post('/updateBuildCheck',[ProjectController::class,'updateBuildCheck'])->name('project.updateBuildCheck')->middleware('can:project-update');
//    Route::post('/updateDev_status',[ProjectController::class,'updateDevStatus'])->name('project.updateDev_status')->middleware('can:project-update');
//    Route::post('/changeKeystoreMultiple',[ProjectController::class,'changeKeystoreMultiple'])->name('project.changeKeystoreMultiple')->middleware('can:project-update');
//    Route::post('/changeSdkMultiple',[ProjectController::class,'changeSdkMultiple'])->name('project.changeSdkMultiple')->middleware('can:project-update');
//
//
//
//    Route::get('/convert',[ProjectController::class,'convert'])->name('project.convert');
//
//
//});

Route::group(['prefix'=>'project','middleware'=>['CheckLogout','2fa']], function (){
    Route::get('/',[Project_Controller::class,'index'])->name('project.index')->middleware('can:project-index');
    Route::post('/getIndex',[Project_Controller::class,'getIndex'])->name('project.getIndex')->middleware('can:project-index');
    Route::post('/create',[Project_Controller::class,'create'])->name('project.create')->middleware('can:project-add');
    Route::get('/edit/{id}',[Project_Controller::class,'edit'])->name('project.edit')->middleware('can:project-edit');
    Route::post('/update',[Project_Controller::class,'update'])->name('project.update')->middleware('can:project-update');
    Route::get('/delete/{id}',[Project_Controller::class,'delete'])->name('project.delete')->middleware('can:project-delete');
    Route::get('/show/{id}',[Project_Controller::class,'show'])->name('project.show');
    Route::get('/check_build',[Project_Controller::class,'check_build'])->name('project.check_build')->middleware('can:project-index');
    Route::post('/updateConsole',[Project_Controller::class,'updateConsole'])->name('project.updateConsole')->middleware('can:project-update');
    Route::post('/updateMultiple',[Project_Controller::class,'updateMultiple'])->name('project.updateMultiple')->middleware('can:project-update');
    Route::post('/updateDev_status',[Project_Controller::class,'updateDevStatus'])->name('project.updateDev_status')->middleware('can:project-update');
    Route::get('/convert',[ProjectController::class,'convert'])->name('project.convert');

    Route::get('/process',[Project_Controller::class,'process'])->name('project.process')->middleware('can:project-index');

    Route::post('/getProcess',[Project_Controller::class,'getProcess'])->name('project.getProcess')->middleware('can:project-index');

    Route::get('/upload',[Project_Controller::class,'upload'])->name('project.upload')->middleware('can:project-index');
    Route::post('/getProjectUpload',[Project_Controller::class,'getProjectUpload'])->name('project.getProjectUpload')->middleware('can:project-index');
    Route::get('/download/{id}',[Project_Controller::class,'download'])->name('project.download')->middleware('can:project-index');
    Route::get('/update_upload_status/{id}',[Project_Controller::class,'update_upload_status'])->name('project.update_upload_status')->middleware('can:project-update');

    Route::get('/fake/{id}',[Project_Controller::class,'fake'])->name('project.fake')->middleware('can:project-index');
    Route::get('/manage',[Project_Controller::class,'manage'])->name('project.manage')->middleware('can:project-index');
    Route::post('/getManage',[Project_Controller::class,'getManage'])->name('project.getManage')->middleware('can:project-index');

});


Route::group(['prefix'=>'cronProject'], function (){
    Route::get('/',[CronProjectController::class,'index'])->name('cronProject.index');
    Route::get('/chplay',[CronProjectController::class,'Chplay'])->name('cronProject.Chplay');
    Route::get('/huawei',[CronProjectController::class,'Huawei'])->name('cronProject.Huawei');
    Route::get('/vivo',[CronProjectController::class,'Vivo'])->name('cronProject.Vivo');
    Route::get('/telegram',[CronProjectController::class,'sendMessTelegram'])->name('cronProject.sendMessTelegram');
});
Route::get('/package',[CronProjectController::class,'getPackage'])->name('cronProject.getPackage');

Route::group(['prefix'=>'template','middleware'=>['CheckLogout','2fa']], function (){
    Route::get('/',[TemplateController::class,'index'])->name('template.index')->middleware('can:template-index');
    Route::post('/getIndex',[TemplateController::class,'getIndex'])->name('template.getIndex')->middleware('can:template-index');
    Route::post('/create',[TemplateController::class,'create'])->name('template.create')->middleware('can:template-add');
    Route::get('/edit/{id}',[TemplateController::class,'edit'])->name('template.edit')->middleware('can:template-edit');
    Route::get('/show/{id}',[TemplateController::class,'edit'])->name('template.show')->middleware('can:template-show');
    Route::post('/update',[TemplateController::class,'update'])->name('template.update')->middleware('can:template-update');
    Route::post('/store',[TemplateController::class,'store'])->name('template.store')->middleware('can:template-update');
    Route::get('/delete/{id}',[TemplateController::class,'delete'])->name('template.delete')->middleware('can:template-delete');
    Route::get('/upload',[TemplateController::class,'upload'])->name('template.upload')->middleware('can:template-index');
    Route::get('/convert',[TemplateController::class,'convert'])->name('template.convert')->middleware('can:template-index');
});

Route::group(['prefix'=>'template-preview'], function (){
    Route::get('/',[TemplatePreviewController::class,'index'])->name('template-preview.index');
    Route::post('/getIndex',[TemplatePreviewController::class,'getIndex'])->name('template-preview.getIndex');
    Route::post('/create',[TemplatePreviewController::class,'create'])->name('template-preview.create')->middleware('can:template-preview-add');
    Route::get('/edit/{id}',[TemplatePreviewController::class,'edit'])->name('template-preview.edit')->middleware('can:template-preview-edit');
    Route::get('/show/{id}',[TemplatePreviewController::class,'edit'])->name('template-preview.show')->middleware('can:template-preview-show');
    Route::post('/update',[TemplatePreviewController::class,'update'])->name('template-preview.update')->middleware('can:template-preview-update');
    Route::get('/delete/{id}',[TemplatePreviewController::class,'delete'])->name('template-preview.delete')->middleware('can:template-preview-delete');
    Route::get('/upload',[TemplatePreviewController::class,'upload'])->name('template-preview.upload')->middleware('can:template-preview-index');
});

Route::group(['prefix'=>'template-text-preview'], function (){
    Route::get('/',[TemplateTextPrController::class,'index'])->name('template-text-preview.index');
    Route::post('/getIndex',[TemplateTextPrController::class,'getIndex'])->name('template-text-preview.getIndex');
    Route::post('/create',[TemplateTextPrController::class,'create'])->name('template-text-preview.create')->middleware('can:template-preview-add');
    Route::get('/edit/{id}',[TemplateTextPrController::class,'edit'])->name('template-text-preview.edit')->middleware('can:template-preview-edit');
    Route::get('/show/{id}',[TemplateTextPrController::class,'edit'])->name('template-text-preview.show')->middleware('can:template-preview-show');
    Route::post('/update',[TemplateTextPrController::class,'update'])->name('template-text-preview.update')->middleware('can:template-preview-update');
    Route::get('/delete/{id}',[TemplateTextPrController::class,'delete'])->name('template-text-preview.delete')->middleware('can:template-preview-delete');
    Route::get('/upload',[TemplateTextPrController::class,'upload'])->name('template-text-preview.upload')->middleware('can:template-preview-index');
});
Route::group(['prefix'=>'data-profile'], function (){
    Route::get('/',[DataProfileController::class,'index'])->name('data_profile.index');
    Route::post('/getIndex',[DataProfileController::class,'getIndex'])->name('data_profile.getIndex');
    Route::post('/create',[DataProfileController::class,'create'])->name('data_profile.create')->middleware('can:template-preview-add');
    Route::get('/edit/{id}',[DataProfileController::class,'edit'])->name('data_profile.edit')->middleware('can:template-preview-edit');
    Route::get('/show/{id}',[DataProfileController::class,'edit'])->name('data_profile.show')->middleware('can:template-preview-show');
    Route::post('/update',[DataProfileController::class,'update'])->name('data_profile.update')->middleware('can:template-preview-update');
    Route::get('/delete/{id}',[DataProfileController::class,'delete'])->name('data_profile.delete')->middleware('can:template-preview-delete');
    Route::get('/upload',[DataProfileController::class,'upload'])->name('data_profile.upload')->middleware('can:template-preview-index');
});

Route::group(['prefix'=>'category_template','middleware'=>['CheckLogout','2fa']], function (){
    Route::get('/',[CategoryTemplateController::class,'index'])->name('category_template.index')->middleware('can:template-preview-index');
    Route::post('/getIndex',[CategoryTemplateController::class,'getIndex'])->name('category_template.getIndex')->middleware('can:template-preview-index');
    Route::post('/create',[CategoryTemplateController::class,'create'])->name('category_template.create')->middleware('can:template-preview-add');
    Route::get('/edit/{id}',[CategoryTemplateController::class,'edit'])->name('category_template.edit')->middleware('can:template-preview-edit');
    Route::get('/show/{id}',[CategoryTemplateController::class,'edit'])->name('category_template.show')->middleware('can:template-preview-show');
    Route::post('/update',[CategoryTemplateController::class,'update'])->name('category_template.update')->middleware('can:template-preview-update');
    Route::get('/delete/{id}',[CategoryTemplateController::class,'delete'])->name('category_template.delete')->middleware('can:template-preview-delete');
    Route::get('/upload',[CategoryTemplateController::class,'upload'])->name('category_template.upload')->middleware('can:template-preview-index');
    Route::get('/get-cate-temp-parent/{id}',[CategoryTemplateController::class,'getCateTempParent'])->name('category_template.getCateTempParent')->middleware('can:template-preview-index');
//    Route::get('/get-temp-preview/{id}',[CategoryTemplateController::class,'getTempText'])->name('category_template.getTempText')->middleware('can:template-preview-index');
});

Route::group(['prefix'=>'category_template_frame','middleware'=>['CheckLogout','2fa']], function (){
    Route::get('/',[CategoryTemplateFrameController::class,'index'])->name('category_template_frame.index')->middleware('can:template-preview-index');
    Route::post('/getIndex',[CategoryTemplateFrameController::class,'getIndex'])->name('category_template_frame.getIndex')->middleware('can:template-preview-index');
    Route::post('/create',[CategoryTemplateFrameController::class,'create'])->name('category_template_frame.create')->middleware('can:template-preview-add');
    Route::get('/edit/{id}',[CategoryTemplateFrameController::class,'edit'])->name('category_template_frame.edit')->middleware('can:template-preview-edit');
    Route::post('/update',[CategoryTemplateFrameController::class,'update'])->name('category_template_frame.update')->middleware('can:template-preview-update');
    Route::get('/delete/{id}',[CategoryTemplateFrameController::class,'delete'])->name('category_template_frame.delete')->middleware('can:template-preview-delete');
    Route::get('/get-temp-preview/{id}',[CategoryTemplateFrameController::class,'getTemp'])->name('category_template.getTemp')->middleware('can:template-preview-index');
});

Route::group(['prefix'=>'build-preview','middleware'=>['CheckLogout','2fa']], function (){
    Route::get('/',[BuildPreviewController::class,'index'])->name('build_preview.index')->middleware('can:template-preview-index');
    Route::post('/getIndex',[BuildPreviewController::class,'getIndex'])->name('build_preview.getIndex')->middleware('can:template-preview-index');
    Route::post('/create',[BuildPreviewController::class,'create'])->name('build_preview.create')->middleware('can:template-preview-add');
    Route::get('/edit/{id}',[BuildPreviewController::class,'edit'])->name('build_preview.edit')->middleware('can:template-preview-edit');
    Route::post('/update',[BuildPreviewController::class,'update'])->name('build_preview.update')->middleware('can:template-preview-update');
    Route::get('/delete/{id}',[BuildPreviewController::class,'delete'])->name('build_preview.delete')->middleware('can:template-preview-delete');
    Route::get('/get-temp-preview/{id}',[BuildPreviewController::class,'getTemp'])->name('build_preview.getTemp')->middleware('can:template-preview-index');
});


Route::group(['prefix'=>'ga_dev','middleware'=>['CheckLogout','2fa']], function (){
    Route::get('/',[Ga_devController::class,'index'])->name('gadev.index')->middleware('can:gadev-index');
    Route::post('/getIndex',[Ga_devController::class,'getIndex'])->name('gadev.getIndex')->middleware('can:gadev-index');
    Route::post('/getIndexV2',[Ga_devController::class,'getIndexV2'])->name('gadev.getIndexV2')->middleware('can:gadev-index');

    Route::post('/create',[Ga_devController::class,'create'])->name('gadev.create')->middleware('can:gadev-add');
    Route::get('/edit/{id}',[Ga_devController::class,'edit'])->name('gadev.edit')->middleware('can:gadev-edit');
    Route::get('/show/{id}',[Ga_devController::class,'show'])->name('gadev.show')->middleware('can:gadev-show');
    Route::post('/update',[Ga_devController::class,'update'])->name('gadev.update')->middleware('can:gadev-update');
    Route::get('/delete/{id}',[Ga_devController::class,'delete'])->name('gadev.delete')->middleware('can:gadev-delete');
});



Route::group(['prefix'=>'dev','middleware'=>['CheckLogout','2fa']], function (){
    Route::get('/',[DevController::class,'index'])->name('dev.index')->middleware('can:dev-index');
    Route::post('/getIndex',[DevController::class,'getIndex'])->name('dev.getIndex')->middleware('can:dev-index');
    Route::post('/create',[DevController::class,'create'])->name('dev.create')->middleware('can:dev-add');
    Route::get('/edit/{id}',[DevController::class,'edit'])->name('dev.edit')->middleware('can:dev-edit');
    Route::get('/show/{id}',[DevController::class,'show'])->name('dev.show')->middleware('can:dev-show');
    Route::post('/update',[DevController::class,'update'])->name('dev.update')->middleware('can:dev-update');
    Route::get('/delete/{id}',[DevController::class,'delete'])->name('dev.delete')->middleware('can:dev-delete');
});

Route::group(['prefix'=>'dev-amazon','middleware'=>['CheckLogout','2fa']], function (){
    Route::get('/',[DevAmazonController::class,'index'])->name('dev_amazon.index')->middleware('can:dev_amazon-index');
    Route::post('/getIndex',[DevAmazonController::class,'getIndex'])->name('dev_amazon.getIndex')->middleware('can:dev_amazon-index');
    Route::post('/create',[DevAmazonController::class,'create'])->name('dev_amazon.create')->middleware('can:dev_amazon-add');
    Route::get('/edit/{id}',[DevAmazonController::class,'edit'])->name('dev_amazon.edit')->middleware('can:dev_amazon-edit');
    Route::get('/show/{id}',[DevAmazonController::class,'show'])->name('dev_amazon.show')->middleware('can:dev_amazon-show');
    Route::post('/update',[DevAmazonController::class,'update'])->name('dev_amazon.update')->middleware('can:dev_amazon-update');
    Route::get('/delete/{id}',[DevAmazonController::class,'delete'])->name('dev_amazon.delete')->middleware('can:dev_amazon-delete');
});

Route::group(['prefix'=>'dev-samsung','middleware'=>['CheckLogout','2fa']], function (){
    Route::get('/',[DevSamsungController::class,'index'])->name('dev_samsung.index')->middleware('can:dev_samsung-index');
    Route::post('/getIndex',[DevSamsungController::class,'getIndex'])->name('dev_samsung.getIndex')->middleware('can:dev_samsung-index');

    Route::post('/create',[DevSamsungController::class,'create'])->name('dev_samsung.create')->middleware('can:dev_samsung-add');
    Route::get('/edit/{id}',[DevSamsungController::class,'edit'])->name('dev_samsung.edit')->middleware('can:dev_samsung-edit');
    Route::get('/show/{id}',[DevSamsungController::class,'show'])->name('dev_samsung.show')->middleware('can:dev_samsung-show');
    Route::post('/update',[DevSamsungController::class,'update'])->name('dev_samsung.update')->middleware('can:dev_samsung-update');
    Route::get('/delete/{id}',[DevSamsungController::class,'delete'])->name('dev_samsung.delete')->middleware('can:dev_samsung-delete');
});

Route::group(['prefix'=>'dev-xiaomi','middleware'=>['CheckLogout','2fa']], function (){
    Route::get('/',[DevXiaomiController::class,'index'])->name('dev_xiaomi.index')->middleware('can:dev_xiaomi-index');
    Route::post('/getIndex',[DevXiaomiController::class,'getIndex'])->name('dev_xiaomi.getIndex')->middleware('can:dev_xiaomi-index');

    Route::post('/create',[DevXiaomiController::class,'create'])->name('dev_xiaomi.create')->middleware('can:dev_xiaomi-add');
    Route::get('/edit/{id}',[DevXiaomiController::class,'edit'])->name('dev_xiaomi.edit')->middleware('can:dev_xiaomi-edit');
    Route::get('/show/{id}',[DevXiaomiController::class,'show'])->name('dev_xiaomi.show')->middleware('can:dev_xiaomi-show');
    Route::post('/update',[DevXiaomiController::class,'update'])->name('dev_xiaomi.update')->middleware('can:dev_xiaomi-update');
    Route::get('/delete/{id}',[DevXiaomiController::class,'delete'])->name('dev_xiaomi.delete')->middleware('can:dev_xiaomi-delete');
});

Route::group(['prefix'=>'dev-oppo','middleware'=>['CheckLogout','2fa']], function (){
    Route::get('/',[DevOppoController::class,'index'])->name('dev_oppo.index')->middleware('can:dev_oppo-index');
    Route::post('/getIndex',[DevOppoController::class,'getIndex'])->name('dev_oppo.getIndex')->middleware('can:dev_oppo-index');

    Route::post('/create',[DevOppoController::class,'create'])->name('dev_oppo.create')->middleware('can:dev_oppo-add');
    Route::get('/edit/{id}',[DevOppoController::class,'edit'])->name('dev_oppo.edit')->middleware('can:dev_oppo-edit');
    Route::get('/show/{id}',[DevOppoController::class,'show'])->name('dev_oppo.show')->middleware('can:dev_oppo-show');
    Route::post('/update',[DevOppoController::class,'update'])->name('dev_oppo.update')->middleware('can:dev_oppo-update');
    Route::get('/delete/{id}',[DevOppoController::class,'delete'])->name('dev_oppo.delete')->middleware('can:dev_oppo-delete');
});

Route::group(['prefix'=>'dev-vivo','middleware'=>['CheckLogout','2fa']], function (){
    Route::get('/',[DevVivoController::class,'index'])->name('dev_vivo.index')->middleware('can:dev_vivo-index');
    Route::post('/getIndex',[DevVivoController::class,'getIndex'])->name('dev_vivo.getIndex')->middleware('can:dev_vivo-index');

    Route::post('/create',[DevVivoController::class,'create'])->name('dev_vivo.create')->middleware('can:dev_vivo-add');
    Route::get('/edit/{id}',[DevVivoController::class,'edit'])->name('dev_vivo.edit')->middleware('can:dev_vivo-edit');
    Route::get('/show/{id}',[DevVivoController::class,'show'])->name('dev_vivo.show')->middleware('can:dev_vivo-show');
    Route::post('/update',[DevVivoController::class,'update'])->name('dev_vivo.update')->middleware('can:dev_vivo-update');
    Route::get('/delete/{id}',[DevVivoController::class,'delete'])->name('dev_vivo.delete')->middleware('can:dev_vivo-delete');
});

Route::group(['prefix'=>'dev-huawei','middleware'=>['CheckLogout','2fa']], function (){
    Route::get('/',[DevHuaweiController::class,'index'])->name('dev_huawei.index')->middleware('can:dev_huawei-index');
    Route::post('/getIndex',[DevHuaweiController::class,'getIndex'])->name('dev_huawei.getIndex')->middleware('can:dev_huawei-index');

    Route::post('/create',[DevHuaweiController::class,'create'])->name('dev_huawei.create')->middleware('can:dev_huawei-add');
    Route::get('/edit/{id}',[DevHuaweiController::class,'edit'])->name('dev_huawei.edit')->middleware('can:dev_huawei-edit');
    Route::get('/show/{id}',[DevHuaweiController::class,'show'])->name('dev_huawei.show')->middleware('can:dev_huawei-show');
    Route::post('/update',[DevHuaweiController::class,'update'])->name('dev_huawei.update')->middleware('can:dev_huawei-update');
    Route::get('/delete/{id}',[DevHuaweiController::class,'delete'])->name('dev_huawei.delete')->middleware('can:dev_huawei-delete');
});


Route::group(['prefix'=>'ga','middleware'=>['CheckLogout','2fa']], function (){
    Route::get('/',[GaController::class,'index'])->name('ga.index')->middleware('can:ga-index');
    Route::post('/create',[GaController::class,'create'])->name('ga.create')->middleware('can:ga-add');
    Route::get('/edit/{id}',[GaController::class,'edit'])->name('ga.edit')->middleware('can:ga-edit');
    Route::get('/showDev/{id}',[GaController::class,'showDev'])->name('ga.showDev')->middleware('can:ga-show');
    Route::get('/show',[GaController::class,'show'])->name('ga.show')->middleware('can:ga-show');
    Route::post('/update',[GaController::class,'update'])->name('ga.update')->middleware('can:ga-update');
    Route::get('/delete/{id}',[GaController::class,'delete'])->name('ga.delete')->middleware('can:ga-delete');
});


Route::group(['prefix'=>'da','middleware'=>['CheckLogout','2fa']], function (){
    Route::get('/',[DaController::class,'index'])->name('da.index')->middleware('can:du_an-index');
    Route::post('/create',[DaController::class,'create'])->name('da.create')->middleware('can:du_an-add');
    Route::get('/edit/{id}',[DaController::class,'edit'])->name('da.edit')->middleware('can:du_an-edit');
    Route::get('/show/{id}',[DaController::class,'show'])->name('da.show')->middleware('can:du_an-show');
    Route::post('/update',[DaController::class,'update'])->name('da.update')->middleware('can:du_an-update');
    Route::get('/delete/{id}',[DaController::class,'delete'])->name('da.delete')->middleware('can:du_an-delete');
});

Route::group(['prefix'=>'khosim','middleware'=>['CheckLogout','2fa']], function (){
    Route::get('/',[KhosimController::class,'index'])->name('khosim.index')->middleware('can:khosim-index');
    Route::get('/getKhosim/', [KhosimController::class, "getKhosim"])->name('khosim.getKhosim');
    Route::post('/create',[KhosimController::class,'create'])->name('khosim.create')->middleware('can:khosim-add');
    Route::get('/edit/{id}',[KhosimController::class,'edit'])->name('khosim.edit')->middleware('can:khosim-edit');
    Route::get('/show/{id}',[KhosimController::class,'show'])->name('khosim.show')->middleware('can:khosim-show');
    Route::post('/update',[KhosimController::class,'update'])->name('khosim.update')->middleware('can:khosim-update');
    Route::get('/delete/{id}',[KhosimController::class,'delete'])->name('khosim.delete')->middleware('can:khosim-delete');
});

Route::group(['prefix'=>'cocsim','middleware'=>['CheckLogout','2fa']], function (){
    Route::get('/',[CocsimController::class,'index'])->name('cocsim.index')->middleware('can:cocsim-index');
    Route::post('/create',[CocsimController::class,'create'])->name('cocsim.create')->middleware('can:cocsim-add');
    Route::get('/edit/{id}',[CocsimController::class,'edit'])->name('cocsim.edit')->middleware('can:cocsim-edit');
    Route::get('/show/{id}',[CocsimController::class,'show'])->name('cocsim.show')->middleware('can:cocsim-show');
    Route::post('/update',[CocsimController::class,'update'])->name('cocsim.update')->middleware('can:cocsim-update');
    Route::get('/delete/{id}',[CocsimController::class,'delete'])->name('cocsim.delete')->middleware('can:cocsim-delete');
});

Route::group(['prefix'=>'hub','middleware'=>['CheckLogout','2fa']], function (){
    Route::get('/',[HubController::class,'index'])->name('hub.index')->middleware('can:hub-index');
    Route::post('/create',[HubController::class,'create'])->name('hub.create')->middleware('can:hub-add');
    Route::get('/edit/{id}',[HubController::class,'edit'])->name('hub.edit')->middleware('can:hub-edit');
    Route::get('/checkbox/{id}',[HubController::class,'checkbox'])->name('hub.checkbox')->middleware('can:hub-edit');
    Route::get('/checkboxAll/{id}',[HubController::class,'checkboxAll'])->name('hub.checkboxAll')->middleware('can:hub-edit');
    Route::get('/show/{id}',[HubController::class,'show'])->name('hub.show')->middleware('can:hub-show');
    Route::post('/update',[HubController::class,'update'])->name('hub.update')->middleware('can:hub-update');
    Route::get('/delete/{id}',[HubController::class,'delete'])->name('hub.delete')->middleware('can:hub-delete');
});


Route::group(['prefix'=>'sms','middleware'=>['CheckLogout','2fa']], function (){
    Route::get('/',[SmsController::class,'index'])->name('sms.index')->middleware('can:sms-index');
    Route::post('/create',[SmsController::class,'create'])->name('sms.create')->middleware('can:sms-add');
    Route::get('/edit/{id}',[SmsController::class,'edit'])->name('sms.edit')->middleware('can:sms-edit');
//    Route::get('/show',[SmsController::class,'show'])->name('sms.show');
    Route::post('/show',[SmsController::class,'showHub'])->name('sms.showHub');
    Route::post('/update',[SmsController::class,'update'])->name('sms.update')->middleware('can:sms-update');
    Route::get('/delete/{id}',[SmsController::class,'delete'])->name('sms.delete')->middleware('can:sms-delete');
    Route::get('/reset',[SmsController::class,'resetSMS'])->name('sms.reset')->middleware('can:sms-index');
});


Route::group(['prefix'=>'mail_manage','middleware'=>['CheckLogout','2fa']], function (){
    Route::get('/',[MailManageController::class,'index'])->name('mail_manage.index')->middleware('can:mail_manage-index');
    Route::post('/create',[MailManageController::class,'create'])->name('mail_manage.create')->middleware('can:mail_manage-add');
    Route::get('/edit/{id}',[MailManageController::class,'edit'])->name('mail_manage.edit')->middleware('can:mail_manage-edit');

    Route::post('/update',[MailManageController::class,'update'])->name('mail_manage.update')->middleware('can:mail_manage-update');
    Route::get('/delete/{id}',[MailManageController::class,'delete'])->name('mail_manage.delete')->middleware('can:mail_manage-delete');
});
Route::get('/2fa/{id}',[MailManageController::class,'show'])->name('mail_manage.show');


Route::group(['prefix'=>'mail_parent','middleware'=>['CheckLogout','2fa']], function (){
    Route::get('/',[MailParentController::class,'index'])->name('mail_parent.index')->middleware('can:mail_parent-index');
    Route::get('/getMailParents/', [MailParentController::class, "getMailParents"])->name('mail_parent.getMailParents');
    Route::get('/noPhone',[MailParentController::class,'indexNo'])->name('mail_parent.indexNo')->middleware('can:mail_parent-index');
    Route::get('/getMailParentsNo/', [MailParentController::class, "getMailParentsNo"])->name('mail_parent.getMailParentsNo');
//    Route::post('/create',[MailManageController::class,'create'])->name('mail_manage.create')->middleware('can:mail_manage-add');
//    Route::get('/edit/{id}',[MailManageController::class,'edit'])->name('mail_manage.edit')->middleware('can:mail_manage-edit');
//    Route::get('/show/{id}',[MailManageController::class,'show'])->name('mail_manage.show')->middleware('can:mail_manage-show');
//    Route::post('/update',[MailManageController::class,'update'])->name('mail_manage.update')->middleware('can:mail_manage-update');
//    Route::get('/delete/{id}',[MailManageController::class,'delete'])->name('mail_manage.delete')->middleware('can:mail_manage-delete');
});


Route::group(['prefix'=>'mail_reg','middleware'=>['CheckLogout','2fa']], function (){
    Route::get('/',[MailRegController::class,'index'])->name('mail_reg.index')->middleware('can:mail_reg-index');
    Route::post('/getMailRegs/', [MailRegController::class, "getMailRegs"])->name('mail_reg.getMailRegs');

});


Route::group(['prefix'=>'device-info','middleware'=>['CheckLogout','2fa']], function (){
    Route::get('/',[DeviceInfoController::class,'index'])->name('device.index')->middleware('can:device-index');
    Route::post('/getIndex', [DeviceInfoController::class, "getIndex"])->name('device.getIndex');
    Route::post('/create',[DeviceInfoController::class,'create'])->name('device.create')->middleware('can:device-add');
    Route::get('/edit/{id}',[DeviceInfoController::class,'edit'])->name('device.edit')->middleware('can:device-edit');
    Route::get('/show/{id}',[DeviceInfoController::class,'show'])->name('device.show')->middleware('can:device-show');
    Route::post('/update',[DeviceInfoController::class,'update'])->name('device.update')->middleware('can:device-update');
    Route::get('/delete/{id}',[DeviceInfoController::class,'delete'])->name('device.delete')->middleware('can:device-delete');
});

Route::group(['prefix'=>'script','middleware'=>['CheckLogout','2fa']], function (){
    Route::get('/',[ScriptController::class,'index'])->name('script.index')->middleware('can:script-index');
    Route::post('/getIndex', [ScriptController::class, "getIndex"])->name('script.getIndex');
    Route::post('/create',[ScriptController::class,'create'])->name('script.create')->middleware('can:script-add');
    Route::get('/edit/{id}',[ScriptController::class,'edit'])->name('script.edit')->middleware('can:script-edit');
    Route::get('/show/{id}',[ScriptController::class,'show'])->name('script.show')->middleware('can:script-show');
    Route::post('/update',[ScriptController::class,'update'])->name('script.update')->middleware('can:script-update');
    Route::get('/delete/{id}',[ScriptController::class,'delete'])->name('script.delete')->middleware('can:script-delete');
});

Route::group(["middleware" => ["auth", "2fa"]], function() {
    Route::get('/home',[HomeController::class,'index']);
    Route::group(["prefix" => "two_face_auths"], function() {
        Route::get('/',[TwoFaceAuthsController::class,'index'])->name('2fa_setting');
        Route::post('/enable',[TwoFaceAuthsController::class,'enable'])->name('enable_2fa_setting');
    });
});

Route::group(["middleware" => ["auth"], "prefix" => "two_face"], function() {
    Route::get('/',[VerifyTwoFaceController::class,'index'])->name('two_face.index');
    Route::post('/verify',[VerifyTwoFaceController::class,'verify'])->name('two_face.verify');
});

Route::group([ "prefix" => "infoIP"], function() {
    Route::get('/',[ipInfoController::class,'index'])->name('inInfo.index');
    Route::post('/getIP',[ipInfoController::class,'getIndex'])->name('inInfo.getIndex');

});

Route::group(['prefix'=>'imei'], function (){
    Route::get('/',[ImeiController::class,'index'])->name('imei.index');
    Route::post('/create',[ImeiController::class,'create'])->name('imei.create');
    Route::get('/gen_imei',[ImeiController::class,'gen_imei'])->name('imei.gen_imei');
    Route::get('/show_imei',[ImeiController::class,'show_imei'])->name('imei.show_imei');
    Route::get('/getBrand',[ImeiController::class,'getBrand'])->name('imei.getBrand');
    Route::get('/import',[ImeiController::class,'import']);
});

Route::group(['prefix'=>'iccid'], function (){
    Route::get('/',[ImeiController::class,'index_iccid'])->name('iccid.index');
//    Route::post('/create',[ImeiController::class,'create'])->name('iccid.create');
    Route::get('/gen_iccid',[ImeiController::class,'gen_iccid'])->name('iccid.gen_iccid');
    Route::get('/show_iccid',[ImeiController::class,'show_iccid'])->name('iccid.show_iccid');
    Route::get('/getCountry',[ImeiController::class,'getCountry'])->name('iccid.getCountry');
//    Route::get('/import',[ImeiController::class,'import']);
});

Route::group(['prefix'=>'keystore','middleware'=>['CheckLogout','2fa']], function (){
    Route::get('/',[KeystoreController::class,'index'])->name('keystore.index')->middleware('can:keystore-index');
    Route::post('/getIndex',[KeystoreController::class,'getIndex'])->name('keystore.getIndex')->middleware('can:keystore-index');
    Route::post('/create',[KeystoreController::class,'create'])->name('keystore.create')->middleware('can:keystore-add');
    Route::get('/edit/{id}',[KeystoreController::class,'edit'])->name('keystore.edit')->middleware('can:keystore-edit');
    Route::get('/show/{id}',[KeystoreController::class,'edit'])->name('keystore.show')->middleware('can:keystore-show');
    Route::post('/update',[KeystoreController::class,'update'])->name('keystore.update')->middleware('can:keystore-update');
    Route::post('/updateMultiple',[KeystoreController::class,'updateMultiple'])->name('keystore.updateMultiple')->middleware('can:keystore-update');
    Route::get('/delete/{id}',[KeystoreController::class,'delete'])->name('keystore.delete')->middleware('can:keystore-delete');
});

Route::group(['prefix'=>'profile','middleware'=>['CheckLogout','2fa']], function (){
    Route::get('/',[ProfileController::class,'index'])->name('profile.index')->middleware('can:dev-index');
    Route::post('/getIndex',[ProfileController::class,'getIndex'])->name('profile.getIndex')->middleware('can:dev-index');
    Route::post('/create',[ProfileController::class,'create'])->name('profile.create')->middleware('can:dev-add');
    Route::post('/create-v2',[ProfileController::class,'create_v2'])->name('profile.create_v2')->middleware('can:dev-add');
    Route::get('/edit/{id}',[ProfileController::class,'edit'])->name('profile.edit')->middleware('can:dev-edit');
    Route::get('/show',[ProfileController::class,'show'])->name('profile.show')->middleware('can:dev-show');
    Route::post('/update',[ProfileController::class,'update'])->name('profile.update')->middleware('can:dev-update');
    Route::get('/delete/{id}',[ProfileController::class,'delete'])->name('profile.delete')->middleware('can:dev-delete');
});


Route::group(['prefix'=>'apk_process'], function (){
    Route::get('/',[Apk_ProcessController::class,'index'])->name('apk_process.index');
    Route::get('success',[Apk_ProcessController::class,'success'])->name('apk_process.success');
    Route::post('getIndex',[Apk_ProcessController::class,'getIndex'])->name('apk_process.getIndex');
    Route::get('/delete/{id}',[Apk_ProcessController::class,'delete'])->name('apk_process.delete');
    Route::get('/update_pss/{id}',[Apk_ProcessController::class,'update_pss'])->name('apk_process.update_pss');
//    Route::get('/delete/{id}',[Apk_ProcessController::class,'delete']);
});

Route::group(['prefix'=>'checkapi','middleware'=>['CheckLogout','2fa']], function (){
    Route::get('/',[CheckApiController::class,'index'])->name('checkapi.index');
    Route::post('/getIndex',[CheckApiController::class,'getIndex'])->name('checkapi.getIndex');
    Route::post('/create',[CheckApiController::class,'create'])->name('checkapi.create');
    Route::get('/edit/{id}',[CheckApiController::class,'edit'])->name('checkapi.edit');

    Route::post('/update',[CheckApiController::class,'update'])->name('checkapi.update');
    Route::get('/delete/{id}',[CheckApiController::class,'delete'])->name('checkapi.delete');
});

Route::group(['prefix'=>'bot',
//    'middleware'=>['CheckLogout','2fa']
], function (){
    Route::get('/',[BotController::class,'index'])->name('bot.index');
    Route::post('/getIndex',[BotController::class,'getIndex'])->name('bot.getIndex');
    Route::post('/create',[BotController::class,'create'])->name('bot.create');
    Route::get('/edit/{id}',[BotController::class,'edit'])->name('bot.edit');
    Route::get('/load_mess/{id}',[BotController::class,'load_mess'])->name('bot.load_mess');
    Route::get('/load_ajax',[BotController::class,'load_ajax'])->name('bot.load_ajax');

    Route::post('/update',[BotController::class,'update'])->name('bot.update');
    Route::get('/delete/{id}',[BotController::class,'delete'])->name('bot.delete');
    Route::get('/delete-all',[BotController::class,'deleteAll'])->name('bot.deleteAll');
    Route::get('/truncate',[BotController::class,'truncate'])->name('bot.truncate');
});

Route::group(['prefix'=>'design','middleware'=>['CheckLogout','2fa']], function (){
    Route::get('/',[DesignController::class,'index'])->name('design.index')->middleware('can:project-index');
    Route::get('/project_show',[DesignController::class,'project_show'])->name('design.project_show')->middleware('can:project-index');
    Route::post('/getIndex', [DesignController::class, "getIndex"])->name('design.getIndex');
    Route::post('/create',[DesignController::class,'create'])->name('design.create')->middleware('can:project-add');
    Route::get('/edit/{id}',[DesignController::class,'edit'])->name('design.edit')->middleware('can:project-edit');
    Route::post('/update',[DesignController::class,'update'])->name('design.update')->middleware('can:project-update');
});

Route::group(['prefix'=>'content','middleware'=>['CheckLogout','2fa']], function (){
    Route::get('/',[ContentController::class,'index'])->name('content.index')->middleware('can:project-index');
    Route::get('/project_show',[ContentController::class,'project_show'])->name('content.project_show')->middleware('can:project-index');
    Route::post('/getIndex', [ContentController::class, "getIndex"])->name('content.getIndex');
    Route::post('/create',[ContentController::class,'create'])->name('content.create')->middleware('can:project-add');
    Route::get('/edit/{id}',[ContentController::class,'edit'])->name('content.edit')->middleware('can:project-edit');

    Route::post('/update',[ContentController::class,'update'])->name('content.update')->middleware('can:project-update');

});

Route::group(['prefix'=>'design-content','middleware'=>['CheckLogout','2fa']], function (){
    Route::get('/',[DesignContentController::class,'index'])->name('design_content.index')->middleware('can:project-index');
    Route::get('/project_show',[DesignContentController::class,'project_show'])->name('design_content.project_show')->middleware('can:project-index');
    Route::post('/getIndex', [DesignContentController::class, "getIndex"])->name('design_content.getIndex');
    Route::post('/create',[DesignContentController::class,'create'])->name('design_content.create')->middleware('can:project-add');
    Route::get('/edit/{id}',[DesignContentController::class,'edit'])->name('design_content.edit')->middleware('can:project-edit');

    Route::post('/update',[DesignContentController::class,'update'])->name('design_content.update')->middleware('can:project-update');

});



Route::get('/fakename',[ProfileController::class,'show'])->name('profile.show');

Route::get('IP2location',[ipInfoController::class,'IP2location'])->name('inInfo.IP2location');
Route::get('2fa',[VerifyTwoFaceController::class,'fa']);















