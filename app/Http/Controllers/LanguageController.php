<?php

namespace App\Http\Controllers;

use App\Facades\AddonFacade;
use App\Models\AddOn;
use App\Models\User;
use App\Models\Utility;
use App\Models\Languages;
use App\Models\NotificationTemplateLangs;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use ZipArchive;

class LanguageController extends Controller
{

    public function manageLanguage($currantLang, $module = 'general')
    {
        $user = Auth::user();
        if (Auth::user()->isAbleTo('language manage')) {
            $languages = Languages::pluck('fullName', 'code');
            $modules = getshowModuleList();
            $settings = getCompanyAllSettings();
            if (!empty($settings['disable_lang'])) {
                $disabledLang = explode(',', $settings['disable_lang']);
            } else {
                $disabledLang = [];
            }
            if ($module == 'general') {
                $dir = base_path() . '/resources/lang/' . $currantLang;
            } else {
                $module = AddOn::where('name', $module)->first();
                if ($module) {
                    $module = $module->name;
                    $this_module = AddonFacade::find($module);
                    $path =   $this_module->getPath();

                    $dir = $path . '/src/Resources/lang/' . $currantLang;
                } else {
                    return redirect()->back()->with('error', __('Please active this module.'));
                }
            }

            try {
                if (file_exists($dir . '.json')) {
                    $arrLabel = json_decode(file_get_contents($dir . '.json'));
                } else {
                    return redirect()->back()->with('error', __('Permission denied.'));
                }
                $arrFiles   = array_diff(
                    scandir($dir),
                    array(
                        '..',
                        '.',
                    )
                );
                $arrMessage = [];
                foreach ($arrFiles as $file) {
                    $fileName = basename($file, ".php");
                    $fileData = $myArray = include $dir . "/" . $file;
                    if (is_array($fileData)) {
                        $arrMessage[$fileName] = $fileData;
                    }
                }
                $langs = Languages::where('code', $currantLang)->first();
            } catch (\Exception $e) {
                return redirect()->back()->with('error', str_replace(array('\'', '"', '`', '{', "\n"), ' ', $e->getMessage()));
            }
            return view('admin.lang.index', compact('languages', 'currantLang', 'arrLabel', 'arrMessage', 'disabledLang', 'settings', 'user', 'module', 'modules'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if (Auth::user()->isAbleTo('language create')) {
            $user = Auth::user();
            return view('admin.lang.create', compact('user'));
        } else {
            return response()->json(['error' => 'Permission Denied.'], 401);
        }
    }

    public function store(Request $request, $lang = 'en', $addOn = "general")
    {
        if (Auth::user()->isAbleTo('language create')) {
            $languageExist = Languages::where('code', $request->code)->orWhere('fullName', $request->fullname)->first();
            if (empty($languageExist)) {
                $language = new Languages();
                $language->code = strtolower($request->code);
                $language->fullName = ucfirst($request->fullName);
                $language->save();
            }
            try {
                $Filesystem = new Filesystem();
                $langCode   = strtolower($request->code);
                $lanfullName = $request->fullName;
                $langDir    = base_path() . '/resources/lang/';
                $dir        = $langDir;
                if (!is_dir($dir)) {
                    mkdir($dir);
                    chmod($dir, 0777);
                }
                $dir      = $dir . '/' . $langCode;
                $jsonFile = $dir . ".json";
                \File::copy($langDir . 'en.json', $jsonFile);

                if (!is_dir($dir)) {
                    mkdir($dir);
                    chmod($dir, 0777);
                }
                $Filesystem->copyDirectory($langDir . "en", $dir . "/");

                $modules = AddonFacade::allModules();
                if ($modules) {
                    foreach ($modules as $module) {
                        $Filesystem = new Filesystem();
                        $langCode   = strtolower($request->code);

                        $addOn = AddOn::where('name', $module->name)->first();
                        if ($addOn) {
                            $path       = $module->getDevPackagePath();
                            $langDir    = $path . '/src/Resources/lang/';
                            $dir        = $langDir;
                            if (!is_dir($dir)) {
                                mkdir($dir);
                                chmod($dir, 0777);
                            }
                            $dir      = $dir . $langCode;

                            $jsonFile = $dir . ".json";

                            if (file_exists($langDir . 'en.json')) {
                                \File::copy($langDir . 'en.json', $jsonFile);
                                chmod($jsonFile, 0777);
                            }
                            if (!is_dir($dir)) {
                                mkdir($dir);
                                chmod($dir, 0777);
                            }

                            $Filesystem->copyDirectory($langDir . "en", $dir . "/");
                        }
                    }
                }
            } catch (\Exception $e) {
                return redirect()->back()->with('error', str_replace(array('\'', '"', '`', '{', "\n"), ' ', $e->getMessage()));
            }
            makeEmailLang($request->code);
            return redirect()->route('admin.lang.index', [$langCode, $addOn])->with('success', __('Language successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    public function storeData(Request $request, $currantLang, $module = 'general')
    {
        $user = Auth::user();
        if ($user->isAbleTo('language manage')) {
            if ($module == 'general') {
                $dir = base_path() . '/resources/lang/';
            } else {
                $modules = AddOn::where('name', $module)->first();
                if (!empty($modules)) {
                    $this_module = AddonFacade::find($modules->name);
                    $path =   $this_module->getPath();
                    $dir = $path . '/src/Resources/lang/';
                } else {
                    return redirect()->back()->with('error', __('Please active this module.'));
                }
            }
            try {

                if (!is_dir($dir)) {
                    mkdir($dir);
                    chmod($dir, 0777);
                }
                $jsonFile = $dir . "/" . $currantLang . ".json";

                file_put_contents($jsonFile, json_encode($request->label));

                $langFolder = $dir . "/" . $currantLang;
                if (!is_dir($langFolder)) {
                    mkdir($langFolder);
                    chmod($langFolder, 0777);
                }
                if (($module == 'general' || $module == '') && (isset($request->message) && !empty($request->message))) {
                    foreach ($request->message as $fileName => $fileData) {
                        $content = "<?php return [";
                        $content .= $this->buildArray($fileData);

                        $content .= "];";
                        file_put_contents($langFolder . "/" . $fileName . '.php', $content);
                    }
                }
            } catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
            return redirect()->route('admin.lang.index', [$currantLang, $module])->with('success', __('Language save successfully'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function buildArray($fileData)
    {
        $content = "";
        foreach ($fileData as $lable => $data) {
            if (is_array($data)) {
                $content .= "'$lable'=>[" . $this->buildArray($data) . "],";
            } else {
                $content .= "'$lable'=>'" . addslashes($data) . "',";
            }
        }

        return $content;
    }

    public function update($lang)
    {
        $user  = Auth::user();
        $data = [
            'name' => 'site_rtl',
            'created_by' => $user->id
        ];
        $user->lang = $lang;
        if ($lang == 'ar' || $lang == 'he') {
            Settings::updateOrCreate($data, ['value' => 'on']);
        } else {
            Settings::updateOrCreate($data, ['value' => 'off']);
        }
        $user->lang = $lang;
        $user->save();
        sideMenuCacheForget();
        companySettingCacheForget();
        return redirect()->back()->with('success', __('Language change successfully.'));
    }

    public function disableLang(Request $request)
    {
        if (Auth::user()->isAbleTo('language enable/disable')) {
            $settings = getCompanyAllSettings();
            $disablelang  = '';
            if ($request->mode == 'off') {
                if (!empty($settings['disable_lang'])) {
                    $disablelang = $settings['disable_lang'];
                    $disablelangArray = explode(',', $disablelang);
                    if (!in_array($request->lang, $disablelangArray)) {
                        $disablelangArray[] = $request->lang;
                    }
                    $disablelang = implode(',', $disablelangArray);
                } else {
                    $disablelang = $request->lang;
                }
                Settings::updateOrCreate(['name' => 'disable_lang'], ['value' => $disablelang, 'created_by' => creatorId()]);
                companySettingCacheForget();
                $data['message'] = __('Language Disabled Successfully');
                $data['status'] = 'success';
                return $data;
            } else {
                $disablelang = $settings['disable_lang'];
                $parts = explode(',', $disablelang);
                // Remove the specified language if it exists
                $parts = array_filter($parts, fn($lang) => $lang !== $request->lang);
                $updatedValue = implode(',', $parts);
                Settings::updateOrCreate(['name' => 'disable_lang'], ['value' => $updatedValue, 'created_by' => creatorId()]);
                companySettingCacheForget();
                $data['message'] = __('Language Enabled Successfully');
                $data['status'] = 'success';
                return $data;
            }
        } else {
            $data['message'] = __('Permission Denied.');
            $data['status'] = 'error';
            return $data;
        }
    }
    public function destroyLang($lang)
    {
        if (Auth::user()->isAbleTo('language delete')) {
            $usr = Auth::user();
            $default_lang = $usr->lang;

            // Remove Email Template Language
            NotificationTemplateLangs::where('lang', 'LIKE', $lang)->delete();

            $langDir = base_path() . '/resources/lang/';
            if (is_dir($langDir)) {
                // remove directory and file
                User::delete_directory($langDir . $lang);
                if (file_exists($langDir . $lang . '.json')) {
                    unlink($langDir . $lang . '.json');
                }
            }

            $modules = AddonFacade::allModules();
            if ($modules) {
                foreach ($modules as $module) {
                    $addOn = AddOn::where('name', $module->name)->first();
                    if ($addOn) {
                        $path       = $module->getPath($addOn->name);
                        $langDir    = $path . '/src/Resources/lang/';
                        if (is_dir($langDir)) {
                            // remove directory and file
                            User::delete_directory($langDir . $lang);
                            if (file_exists($langDir . $lang . '.json')) {
                                unlink($langDir . $lang . '.json');
                            }
                        }
                    }
                }
            }
            // update user that has assign deleted language.
            User::where('lang', 'LIKE', $lang)->update(['lang' => $default_lang]);
            Languages::where('code', $lang)->first()->delete();

            return redirect()->route('admin.lang.index', $default_lang)->with('success', __('The language has been deleted'));
        } else {
            return redirect()->back()->with('error', 'Permission Denied.');
        }
    }

    public function exportLanguageJson(Request  $request)
    {
        if (Auth::user()->isAbleTo('language manage')) {
            $tempDirectory = storage_path('temp-lang-files');
            if (!file_exists($tempDirectory)) {
                mkdir($tempDirectory, 0755, true);
            }

            $languageDirectory = resource_path('lang');
            $baseJsonFiles = glob($languageDirectory . DIRECTORY_SEPARATOR . "*.json");
            foreach ($baseJsonFiles as $filePath) {
                $fileName = basename($filePath);
                $newFileName = 'base-' . $fileName;
                copy($filePath, $tempDirectory . DIRECTORY_SEPARATOR . $newFileName);
            }

            $modules = AddonFacade::all();
            foreach ($modules as $module) {
                $moduleLangDirectories = base_path('packages/workdo/' . $module->name . '/src/Resources/lang');
                $moduleJsonFiles = glob($moduleLangDirectories . DIRECTORY_SEPARATOR . "*.json");
                foreach ($moduleJsonFiles as $moduleFilePath) {
                    $moduleFileName = basename($moduleFilePath);
                    $newModuleFileName = $module->name . '-' . $moduleFileName;
                    copy($moduleFilePath, $tempDirectory . DIRECTORY_SEPARATOR . $newModuleFileName);
                }
            }

            $exportableFileName = 'language-file-version-' . config('verification.system_version') . '.zip';
            $zipFilePath = storage_path($exportableFileName);
            $zip = new ZipArchive;
            if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
                $renamedFiles = glob($tempDirectory . DIRECTORY_SEPARATOR . '*.json');
                foreach ($renamedFiles as $renamedFilePath) {
                    $zip->addFile($renamedFilePath, basename($renamedFilePath));
                }
                $zip->close();
            } else {
                return redirect()->back()->with('error', __('Could not create ZIP file'));
            }

            array_map('unlink', glob($tempDirectory . DIRECTORY_SEPARATOR . '*'));
            rmdir($tempDirectory);

            return response()->download($zipFilePath)->deleteFileAfterSend(true);
        } else {
            return redirect()->back()->with('error', __('Permission Denied'));
        }
    }

    public function importLangJsonUpload(Request $request)
    {
        if (Auth::user()->isAbleTo('language manage')) {
            return view('admin.lang.import-language');
        } else {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    public function importLangJsonProcess(Request $request)
    {
        if (Auth::user()->isAbleTo('language manage')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'file' => 'required|file|mimes:zip',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $file = $request->file('file');
            $tempPath = storage_path('temp-zip');
            $zip = new ZipArchive;
            if ($zip->open($file->getPathname()) === TRUE) {
                $zip->extractTo($tempPath);
                $zip->close();
            } else {
                return redirect()->back()->with('error', __('Failed to extract the ZIP file'));
            }

            $files = glob($tempPath . '/*.json');
            foreach ($files as $uploadedFilePath) {
                $uploadedFileName = basename($uploadedFilePath);

                if (str_starts_with($uploadedFileName, 'base-')) {
                    $language = str_replace('base-', '', $uploadedFileName);
                    $existingFilePath = resource_path('lang/' . $language);

                    $this->mergeJsonFiles($existingFilePath, $uploadedFilePath);
                } else {
                    [$module, $language] = explode('-', $uploadedFileName, 2);
                    $existingFilePath = base_path("packages/workdo/{$module}/src/Resources/lang/{$language}");
                    $this->mergeJsonFiles($existingFilePath, $uploadedFilePath);
                }
            }

            array_map('unlink', glob($tempPath . '/*'));
            rmdir($tempPath);

            return redirect()->back()->with('success', __('Files Merged Successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied'));
        }
    }

    private function mergeJsonFiles($existingFilePath, $uploadedFilePath)
    {
        $existingData = file_exists($existingFilePath) ? json_decode(file_get_contents($existingFilePath), true) : [];

        $uploadedData = json_decode(file_get_contents($uploadedFilePath), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("Invalid JSON in file: $uploadedFilePath");
        }

        $mergedData = array_merge($existingData, $uploadedData);

        file_put_contents($existingFilePath, json_encode($mergedData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}
