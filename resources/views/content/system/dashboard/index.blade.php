@extends('layouts.system', [
    'wsecond_title' => 'Dashboard',
    'sidebar_menu' => 'dashboard',
    'sidebar_submenu' => null,
    'wheader' => [
        'header_title' => 'Dashboard',
        'header_breadcrumb' => [
            [
                'title' => 'Dashboard',
                'is_active' => true,
                'url' => false
            ],
        ]
    ]
])