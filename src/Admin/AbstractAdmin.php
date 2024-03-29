<?php


namespace Adeliom\WP\Extensions\Admin;

use Traversable;
use function Sober\Intervention\intervention;

/**
 * Class AbstractAdmin
 * @package Adeliom\WP\Extensions\Admin
 */
abstract class AbstractAdmin
{
    /**
     * Register the admin Block
     * @return void
     */
    public static function register(): void
    {
        if(static::hasOptionPage()){
            $options = static::setupOptionPage();
            if(function_exists("Sober\Intervention\intervention")){
                intervention('add-acf-page', $options["settings"], $options["roles"]);
            }
        }

        register_extended_field_group([
            'title' => static::getTitle(),
            'style' => static::getStyle(),
            'fields' => iterator_to_array(static::getFields()),
            'location' => iterator_to_array(static::getLocation()),
            'position' => static::getPosition(),
            'label_placement' => static::getLabelPlacement(),
            'instruction_placement' => static::getInstructionPlacement(),
            'hide_on_screen' => static::getHideOnScreen(),
            'menu_order' => static::getMenuOrder(),
        ]);
    }

    /**
     * Visible in metabox handle
     * @return string
     */
    abstract public static function getTitle(): string;

    /**
     * Get the admin block style
     * @return string
     */
    public static function getStyle(): string
    {
        return "seamless";
    }

    /**
     * Determines the position on the edit screen. Defaults to acf_after_title. Choices of 'acf_after_title', 'normal' or 'side'
     * @return string
     */
    public static function getPosition(): string
    {
        return "acf_after_title";
    }

    /**
     * Determines where field labels are places in relation to fields. Defaults to 'top'. Choices of 'top' (Above fields) or 'left' (Beside fields)
     * @return string
     */
    public static function getLabelPlacement(): string
    {
        return "top";
    }

    /**
     * Determines where field instructions are places in relation to fields. Defaults to 'label'. Choices of 'label' (Below labels) or 'field' (Below fields)
     * @return string
     */
    public static function getInstructionPlacement(): string
    {
        return "label";
    }

    /**
     * An array of elements to hide on the screen
     * @return array
     */
    public static function getHideOnScreen(): array
    {
        return [];
    }

    /**
     * Field groups are shown in order from lowest to highest. Defaults to 0
     * @return int
     */
    public static function getMenuOrder(): int
    {
        return 0;
    }

    /**
     * Register has option page
     * @return bool
     */
    public static function hasOptionPage(): bool
    {
        return false;
    }

    /**
     * Register option page settings
     * @return array
     */
    public static function setupOptionPage(): array
    {
        return [
            "settings" => "Options",
            "roles" => ['admin', 'editor']
        ];
    }

    /**
     * An list of fields
     * @return Traversable
     */
    abstract public static function getFields(): Traversable;

    /**
     * An list containing 'rule groups' where each 'rule group' is an array containing 'rules'. Each group is considered an 'or', and each rule is considered an 'and'.
     * @return Traversable
     */
    abstract public static function getLocation(): Traversable;
}
