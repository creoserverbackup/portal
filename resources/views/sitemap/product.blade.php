<?php echo '<?xml version="1.0" encoding="UTF-8"?>' ?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach($products as $item)
        @if(empty($item->path))
            @continue
        @endif
        <url>
            <loc>{{ $domain }}{{ $item->path }}</loc>
            <lastmod>{{ gmdate(DateTime::W3C, strtotime($item->updated_at)) }}</lastmod>
            <changefreq>daily</changefreq>
            <priority>1.0</priority>
        </url>
    @endforeach
</urlset>
