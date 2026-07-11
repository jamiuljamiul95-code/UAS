<?php

namespace App\controllers;

class PageController extends BaseController
{
    /**
     * GET /about
     */
    public function about(): void
    {
        $this->view('frontend/about', [
            'title' => 'Tentang Kami — Mizu Design',
        ]);
    }
    // GET /faq
    public function faq(): void
    {
        $this->view('frontend/faq', [
            'title' => 'Bantuan & FAQ — Mizu Design',
        ]);
    }
}