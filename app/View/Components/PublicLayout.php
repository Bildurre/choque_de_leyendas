<?php

namespace App\View\Components;

use Illuminate\View\Component;

class PublicLayout extends Component
{
  /**
   * The page title.
   *
   * @var string
   */
  public $title;

  /**
   * The meta description.
   *
   * @var string
   */
  public $metaDescription;

  /**
   * The Open Graph title.
   *
   * @var string
   */
  public $ogTitle;

  /**
   * The Open Graph description.
   *
   * @var string
   */
  public $ogDescription;

  /**
   * The Open Graph type.
   *
   * @var string
   */
  public $ogType;

  /**
   * The Open Graph image.
   *
   * @var string
   */
  public $ogImage;

  /**
   * Additional meta tags.
   *
   * @var string
   */
  public $metaTags;

  /**
   * Create a new component instance.
   *
   * @return void
   */
  public function __construct(
    $title = null,
    $metaDescription = null,
    $ogTitle = null,
    $ogDescription = null,
    $ogType = 'website',
    $ogImage = null,
    $metaTags = null
  ) {
    $this->title = $title ?? config('app.name', 'Alanda - Choque de Leyendas');
    $this->metaDescription = $metaDescription ?? 'Explora el mundo de Alanda - Choque de Leyendas. Descubre todas las cartas, héroes y facciones del juego de cartas estratégico.';
    $this->ogTitle = $ogTitle ?? $this->title;
    $this->ogDescription = $ogDescription ?? $this->metaDescription;
    $this->ogType = $ogType;
    $this->ogImage = $ogImage ?? asset('images/og-default.jpg');
    $this->metaTags = $metaTags;
  }

  /**
   * Get the view / contents that represent the component.
   *
   * @return \Illuminate\Contracts\View\View|\Closure|string
   */
  public function render()
  {
    return view('layouts.public');
  }
}