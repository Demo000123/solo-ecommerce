<?php
declare(strict_types=1);

namespace App\Services;

use App\Core\Database;

class SettingService
{
    private Database $db;
    private array $settings = [];
    private bool $loaded = false;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAllSettings(): array
    {
        $sql = "SELECT * FROM settings ORDER BY setting_key ASC";
        return $this->db->query($sql);
    }

    public function getSetting(string $key, $default = null)
    {
        // Load all settings if not loaded yet
        if (!$this->loaded) {
            $this->loadSettings();
        }
        
        return $this->settings[$key] ?? $default;
    }

    public function updateSetting(string $key, string $value): bool
    {
        // Check if setting exists
        if ($this->settingExists($key)) {
            $sql = "UPDATE settings SET setting_value = ?, updated_at = NOW() WHERE setting_key = ?";
            $result = $this->db->execute($sql, [$value, $key]);
        } else {
            $sql = "INSERT INTO settings (setting_key, setting_value, created_at) VALUES (?, ?, NOW())";
            $result = $this->db->execute($sql, [$key, $value]);
        }
        
        // Update cache
        if ($result) {
            $this->settings[$key] = $value;
        }
        
        return $result;
    }

    public function createSetting(string $key, string $value): bool
    {
        // Check if setting already exists
        if ($this->settingExists($key)) {
            return false;
        }
        
        $sql = "INSERT INTO settings (setting_key, setting_value, created_at) VALUES (?, ?, NOW())";
        $result = $this->db->execute($sql, [$key, $value]);
        
        // Update cache
        if ($result) {
            $this->settings[$key] = $value;
        }
        
        return $result;
    }

    public function deleteSetting(string $key): bool
    {
        $sql = "DELETE FROM settings WHERE setting_key = ?";
        $result = $this->db->execute($sql, [$key]);
        
        // Update cache
        if ($result && isset($this->settings[$key])) {
            unset($this->settings[$key]);
        }
        
        return $result;
    }

    public function settingExists(string $key): bool
    {
        $sql = "SELECT COUNT(*) as count FROM settings WHERE setting_key = ?";
        $result = $this->db->query($sql, [$key]);
        
        return (int)($result[0]['count'] ?? 0) > 0;
    }

    private function loadSettings(): void
    {
        $settings = $this->getAllSettings();
        
        foreach ($settings as $setting) {
            $this->settings[$setting['setting_key']] = $setting['setting_value'];
        }
        
        $this->loaded = true;
    }

    public function getSettingByKey(string $key): ?array
    {
        $sql = "SELECT * FROM settings WHERE setting_key = ?";
        $settings = $this->db->query($sql, [$key]);
        
        return $settings[0] ?? null;
    }

    public function getSettingValue(string $key, $default = null)
    {
        $setting = $this->getSettingByKey($key);
        
        return $setting ? $setting['setting_value'] : $default;
    }

    public function initializeDefaultSettings(): void
    {
        $defaultSettings = [
            'site_name' => 'Solo E-commerce',
            'site_description' => 'A simple e-commerce platform',
            'contact_email' => 'contact@example.com',
            'contact_phone' => '+1234567890',
            'address' => '123 Main St, City, Country',
            'currency' => 'VND',
            'facebook_url' => 'https://facebook.com',
            'instagram_url' => 'https://instagram.com',
            'twitter_url' => 'https://twitter.com'
        ];
        
        foreach ($defaultSettings as $key => $value) {
            if (!$this->settingExists($key)) {
                $this->createSetting($key, $value);
            }
        }
    }
} 