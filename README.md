# Beam-Parsedown

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
![Test](https://github.com/ardissoebrata/beam-parsedown/workflows/Test/badge.svg)

Beam-Parsedown is a Laravel package of [ParsedownExtra](https://github.com/erusev/parsedown-extra) which adds several new features in it.

Here's a list of the added features:

- [BasePath](#user-content-basepath)
- [Header ID](#user-content-header-id)
- [Icons](#user-content-icons)
- [Audio](#user-content-audio)
- [Youtube](#user-content-youtube)
- [Alerts](#user-content-alerts)
- [Drawio](#user-content-drawio)
- [Mermaid JS](#user-content-mermaid-js)
- [Chart JS](#user-content-chart-js)

## Installation

Via Composer

``` bash
$ composer require ardissoebrata/beam-parsedown
```

## Usage

``` php
$html = BeamParsedown::text($markdown);
```

### BasePath

Set default base path for images, audios & drawio diagrams. It will only add base path to relative urls (ex. `./image.png`).

``` php
$html = BeamParsedown::setBasePath($basepath)->text($markdown);
```

### Header ID

Automatically add IDs to headings.

**Markdown**
``` markdown
# Heading 1

## Heading 2

### Heading 3
```

**Results**
``` html
<h1 id="heading-1">Heading 1</h1>

<h2 id="heading-2">Heading 2</h2>

<h3 id="heading-3">Heading 3</h3>
```

### Icons

You can easily display fontawesome icons. To add an icon, simply write `icon:` followed with the class icon that you want to display in square brackets.

**Markdown**
``` markdown
[icon: fa fa-home]

# [icon: fa fa-home] Home

## [icon: fa fa-home] Home 2 {#the-site .main .shine lang=fr}
```

**Results**
``` html
<p><i class="fa fa-home"></i></p>
<h1 id="home"><i class="fa fa-home"></i> Home</h1>
<h2 id="the-site" lang="fr" class="main shine"><i class="fa fa-home"></i> Home 2</h2>
```

### Audio

To embed audio, add `audio:` followed by the link to audio file (m4a).

**Markdown**
``` markdown
[audio: audios.m4a]
[audio: http://other-example.com/audios.m4a]
```

**Results**
``` html
<p><audio controls="" preload="none"><source src="http://basepath.test/data/audios.m4a" /></audio></p>
<p><audio controls="" preload="none"><source src="http://other-example.com/audios.m4a" /></audio></p>
```

### Youtube

Display youtube videos from shared links. Write `youtube:` followed by shared links from Youtube, enclosed in square bracket.

**Markdown**
``` markdown
[youtube: https://youtu.be/videoid]
```

**Results**
``` html
<div class="overflow-hidden relative h-0" style="padding-bottom: 56.25%"><iframe src="https://www.youtube.com/embed/videoid" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen="" class="left-0 top-0 h-full w-full absolute"></iframe></div>
```

### Alerts

To display note/info block in document, enclosed block with `info` followed by three tick marks (\`\`\`).

**Markdown**
``` markdown
:::info
**Note:** Note contents live here.

And here too. Add blank line to test multiline alerts.
:::

:::warning
**Warning:** Warning contents live here.
:::
```

**Results**
``` html
<div class="bg-indigo-100 rounded shadow-sm flex overflow-hidden" role="alert">
<div class="bg-indigo-500 w-20 flex justify-center items-center"><i class="fa fa-info-circle fa-2x text-white"></i></div>
<div class="flex-1 px-4">
<p><strong>Note:</strong> Note contents live here.
And here too. Add blank line to test multiline alerts.</p>
</div>
</div>

<div class="bg-yellow-50 rounded shadow-sm flex overflow-hidden" role="alert">
<div class="bg-yellow-300 w-20 flex justify-center items-center"><i class="fa fa-exclamation-triangle fa-2x"></i></div>
<div class="flex-1 px-4">
<p><strong>Warning:</strong> Warning contents live here.</p>
</div>
</div>
```

### Draw.io

To embed **draw.io** diagrams, add `drawio:` followed by the link to drawio file (`.drawio`).

**Markdown**
``` markdown
[drawio: sample.drawio]
```

**Results**
``` html
<div>
<div class="mxgraph w-full border" data-mxgraph="{&quot;highlight&quot;:&quot;#0000ff&quot;,&quot;target&quot;:&quot;blank&quot;,&quot;nav&quot;:true,&quot;resize&quot;:true,&quot;toolbar&quot;:&quot;zoom layers lightbox&quot;,&quot;url&quot;:&quot;http:\/\/basepath.test\/data\/sample.drawio&quot;}"></div>
<script type="text/javascript" src="https://viewer.diagrams.net/js/viewer-static.min.js"></script>
</div>
```

### Mermaid JS

Here is one mermaid diagram:

**Markdown**
``` markdown
:::mermaid
graph TD
    A[Client] --> B[Load Balancer]
    B --> C[Server1]
    B --> D[Server2]
:::
```

**Results**
```html
<div class="mermaid">
graph TD
    A[Client] --> B[Load Balancer]
    B --> C[Server1]
    B --> D[Server2]
</div>
```

### Chart JS

Show charts with chart.js config:

**Markdown**
``` markdown
:::chart
{
  "type": "line",
  "data": {
	  ...
  },
  "options": {
  }
}
:::
```

**Results**
``` html
<canvas class="chartjs">
{
  "type": "line",
  "data": {
	  ...
  },
  "options": {
  }
}
</canvas>
```

### Configuration

To add new or edit the default options, run the following command to make a copy of the default configuration file:

``` bash
php artisan vendor:publish --tag=beam-parsedown.config --force
```

## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Credits

- [Ardi Soebrata][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/ardissoebrata/beam-parsedown.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/ardissoebrata/beam-parsedown.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/ardissoebrata/beam-parsedown
[link-downloads]: https://packagist.org/packages/ardissoebrata/beam-parsedown
[link-author]: https://github.com/ardissoebrata
[link-contributors]: ../../contributors