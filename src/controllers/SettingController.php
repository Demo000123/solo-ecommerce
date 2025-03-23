<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Services\SettingService;

class SettingController extends Controller
{
    private SettingService $settingService;

    public function __construct()
    {
        $this->settingService = new SettingService();
    }

    public function adminSettings(): void
    {
        // Check if user is admin
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $this->redirect('/login');
            return;
        }

        $error = '';
        $success = '';

        // Get all settings
        $settings = $this->settingService->getAllSettings();
        $formattedSettings = [];

        // Format settings into an associative array for easier access
        foreach ($settings as $setting) {
            $formattedSettings[$setting['setting_key']] = $setting['setting_value'];
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get general settings
            $siteName = $this->filterInput($this->postParam('site_name', ''));
            $siteDescription = $this->filterInput($this->postParam('site_description', ''));
            $contactEmail = $this->filterInput($this->postParam('contact_email', ''));
            $contactPhone = $this->filterInput($this->postParam('contact_phone', ''));
            $address = $this->filterInput($this->postParam('address', ''));
            $currency = $this->filterInput($this->postParam('currency', 'VND'));

            // Get social media settings
            $facebookUrl = $this->filterInput($this->postParam('facebook_url', ''));
            $instagramUrl = $this->filterInput($this->postParam('instagram_url', ''));
            $twitterUrl = $this->filterInput($this->postParam('twitter_url', ''));

            // Create an array of settings to update
            $updatedSettings = [
                'site_name' => $siteName,
                'site_description' => $siteDescription,
                'contact_email' => $contactEmail,
                'contact_phone' => $contactPhone,
                'address' => $address,
                'currency' => $currency,
                'facebook_url' => $facebookUrl,
                'instagram_url' => $instagramUrl,
                'twitter_url' => $twitterUrl
            ];

            // Update each setting
            $updateCount = 0;
            foreach ($updatedSettings as $key => $value) {
                $updated = $this->settingService->updateSetting($key, $value);
                if ($updated) {
                    $updateCount++;
                    // Update the formatted settings for display
                    $formattedSettings[$key] = $value;
                }
            }

            if ($updateCount > 0) {
                $success = 'Settings updated successfully';
            } else {
                $error = 'No settings were updated';
            }
        }

        $this->render('admin/settings/index', [
            'pageTitle' => 'Site Settings',
            'settings' => $formattedSettings,
            'error' => $error,
            'success' => $success
        ]);
    }

    public function adminAddSetting(): void
    {
        // Check if user is admin
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $this->redirect('/login');
            return;
        }

        // Only process POST requests
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/settings');
            return;
        }

        $key = $this->filterInput($this->postParam('key', ''));
        $value = $this->filterInput($this->postParam('value', ''));

        if (empty($key)) {
            $_SESSION['admin_error'] = 'Setting key cannot be empty';
            $this->redirect('/admin/settings');
            return;
        }

        // Check if the setting already exists
        if ($this->settingService->settingExists($key)) {
            $_SESSION['admin_error'] = 'Setting with this key already exists';
            $this->redirect('/admin/settings');
            return;
        }

        // Create the new setting
        $created = $this->settingService->createSetting($key, $value);

        if ($created) {
            $_SESSION['admin_message'] = 'Setting added successfully';
        } else {
            $_SESSION['admin_error'] = 'Failed to add setting';
        }

        $this->redirect('/admin/settings');
    }

    public function adminDeleteSetting(): void
    {
        // Check if user is admin
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $this->redirect('/login');
            return;
        }

        // Only process POST requests
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/settings');
            return;
        }

        $key = $this->filterInput($this->postParam('key', ''));

        if (empty($key)) {
            $_SESSION['admin_error'] = 'Setting key cannot be empty';
            $this->redirect('/admin/settings');
            return;
        }

        // Check if it's a core setting that shouldn't be deleted
        $coreSettings = [
            'site_name', 'site_description', 'contact_email', 'contact_phone', 
            'address', 'currency', 'facebook_url', 'instagram_url', 'twitter_url'
        ];

        if (in_array($key, $coreSettings)) {
            $_SESSION['admin_error'] = 'Cannot delete core settings';
            $this->redirect('/admin/settings');
            return;
        }

        // Delete the setting
        $deleted = $this->settingService->deleteSetting($key);

        if ($deleted) {
            $_SESSION['admin_message'] = 'Setting deleted successfully';
        } else {
            $_SESSION['admin_error'] = 'Failed to delete setting';
        }

        $this->redirect('/admin/settings');
    }

    // Helper method to get a setting value
    public static function get(string $key, $default = null)
    {
        $settingService = new SettingService();
        return $settingService->getSetting($key, $default);
    }
} 