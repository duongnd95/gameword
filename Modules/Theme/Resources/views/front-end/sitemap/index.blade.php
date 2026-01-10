<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd http://www.google.com/schemas/sitemap-image/1.1 http://www.google.com/schemas/sitemap-image/1.1/sitemap-image.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach ($news as $item)
        <url>
            <loc>{{ url(optional($item->category)->slug . '/' .$item->slug) }}.html</loc>
            <lastmod>{{ $item->created_at->tz('UTC')->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.9</priority>   
            <image:image>
                <image:loc>{{ asset($item->image) }}</image:loc>
		    </image:image>
        </url>
    @endforeach
    @foreach($category as $item)
    <url>
        <loc>{{ url('tin-tuc/'.$item->slug) }}</loc>
        <lastmod>{{ $item->created_at->tz('UTC')->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>   
    </url>
    @endforeach
    @foreach ($products as $item)
    <url>
        <loc>{{ url(optional($item->category)->slug . '/' .$item->slug) }}.html</loc>
        <lastmod>{{ $item->created_at->tz('UTC')->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority> 
        <image:image>
                <image:loc>{{ asset($item->image) }}</image:loc>
		</image:image>  
    </url>
    @endforeach
    @foreach($categoryProducts as $item)
    <url>
        <loc>{{ url('san-pham/'.$item->slug) }}</loc>
        <lastmod>{{ $item->updated_at->tz('UTC')->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>   
    </url>
    @endforeach
    @foreach ($pages as $item)
    <url>
        <loc>{{ url($item->slug) }}.html</loc>
        <lastmod>{{ $item->created_at->tz('UTC')->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>   
    </url>
    @endforeach
</urlset> 