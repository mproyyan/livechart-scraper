<?php

namespace Tests\Unit\Models;

use App\Facades\Goutte;
use Tests\TestCase;
use Symfony\Component\DomCrawler\Crawler;
use App\Models\AnimeOva;

class CrawlAnimeOvaPageTest extends TestCase
{
    private string $dummyHtml = <<<HTML
        <main class="chart" data-controller="anime-card-list">
            <article class="anime" data-anime-id="10886" data-romaji="Mushoku Tensei: Isekai Ittara Honki Dasu 2nd Cour Extra Edition" data-english="Mushoku Tensei: Jobless Reincarnation Part 2 Extra Edition" data-native="無職転生~異世界行ったら本気だす~ 第2クール 番外編" data-alternate="[&quot;Jobless Reincarnation: I Will Seriously Try If I Go To Another World 2nd Season&quot;,&quot;Mushoku Tensei: Jobless Reincarnation 2nd Cour&quot;,&quot;无职转生～到了异世界就拿出真本事～&quot;,&quot;무직전생 이세계에 갔으면 최선을 다한다&quot;,&quot;Thất nghiệp chuyển sinh: Sang thế giới khác tôi sẽ nghiêm túc&quot;,&quot;เกิดชาตินี้พี่ต้องเทพ&quot;,&quot;무직전생 ~이세계에 갔으면 최선을 다한다~ 2기&quot;,&quot;Mushoku Tensei: Jobless Reincarnation - Eris the Goblin Slayer&quot;]" data-premiere="1647356400" data-premiere-precision="3" data-controller="anime-card" data-anime-card-list-target="card" data-library-status="">
                <div class="anime-card">
                    <div class="anime-card--mark-menu" data-anime-card-target="marksMenu" data-action="mouseover->anime-card#showMarkMenu mouseout->anime-card#hideMarkMenu">
                        <div class="anime-card--mark-menu--item" data-action="click->anime-card#setLibraryStatus" data-library-status="completed">Completed</div>
                        <div class="anime-card--mark-menu--item" data-action="click->anime-card#setLibraryStatus" data-library-status="watching">Watching</div>
                        <div class="anime-card--mark-menu--item" data-action="click->anime-card#setLibraryStatus" data-library-status="considering">Considering</div>
                        <div class="anime-card--mark-menu--item" data-action="click->anime-card#setLibraryStatus" data-library-status="skipping">Skipping</div>
                    </div>
                    <div class="mark-icon anime-card--mark-button" data-controller="mark-icon" data-action="click->anime-card#showLibraryInput" data-mark-icon-viewer-status-value="" title="My list">
                        <svg viewBox="0 0 24 24" data-mark-icon-target="canvas">
                            <use class="primary-path" data-mark-icon-target="primaryPath" href="#mark:none"></use>
                        </svg>
                    </div>
                    <h3 class="main-title">
                        <a data-anime-card-target="mainTitle" href="/anime/10886">Mushoku Tensei: Isekai Ittara Honki Dasu 2nd Cour Extra Edition</a>
                    </h3>
                    <ol class="anime-tags">
                        <li><a href="/tags/8">Drama</a></li>
                        <li><a href="/tags/10">Fantasy</a></li>
                        <li><a href="/tags/21">Magic</a></li>
                    </ol>
                    <div class="poster-container">
                        <img class="lazy-img" width="175" height="250" src="https://u.livechart.me/anime/10886/poster_image/f003d8256759bf415a18b4ce212b4ff0.webp?style=small&amp;format=webp" alt="Mushoku Tensei: Isekai Ittara Honki Dasu 2nd Cour Extra Edition" data-src="https://u.livechart.me/anime/10886/poster_image/f003d8256759bf415a18b4ce212b4ff0.webp?style=small&amp;format=jpg" data-srcset="https://u.livechart.me/anime/10886/poster_image/f003d8256759bf415a18b4ce212b4ff0.webp?style=small&amp;format=jpg 1x, https://u.livechart.me/anime/10886/poster_image/f003d8256759bf415a18b4ce212b4ff0.webp?style=large&amp;format=jpg 2x" data-anime-card-target="poster" data-lazy-loaded="true" srcset="https://u.livechart.me/anime/10886/poster_image/f003d8256759bf415a18b4ce212b4ff0.webp?style=small&amp;format=webp 1x, https://u.livechart.me/anime/10886/poster_image/f003d8256759bf415a18b4ce212b4ff0.webp?style=large&amp;format=webp 2x">
                        <div class="anime-extras">
                            <div class="anime-avg-user-rating" title="8.08 out of 10 based on 558 user ratings" data-action="click->anime-card#showLibraryEditor">
                                <i class="icon-star"></i>8.08 
                            </div>
                        </div>
                    </div>
                    <div class="anime-info">
                        <ul class="anime-studios">
                            <li><a href="/studios/1517" data-anime-card-target="studioLink">Studio Bind</a></li>
                        </ul>
                        <div class="anime-date" data-action="click->anime-card#showPremiereDateTime">March 16, 2022 JST</div>
                        <div class="anime-metadata">
                            <div class="anime-source">Light Novel</div>
                            <div class="anime-episodes">24m</div>
                        </div>
                        <div class="anime-synopsis" data-anime-card-target="synopsis">
                            <p>Extra unaired episode included in the fourth Blu-ray volume.</p>
                        </div>
                    </div>
                    <ul class="related-links">
                        <li><a class="website-icon" href="https://mushokutensei.jp/" target="_blank" rel="noopener nofollow"></a></li>
                        <li><a class="preview-icon" href="/anime/10886/videos" data-action="anime-card#showVideos"></a></li>
                        <li><a class="watch-icon" href="/anime/10886/streams" data-action="anime-card#showStreams"></a></li>
                        <li><a class="twitter-icon" href="https://twitter.com/mushokutensei_A" target="_blank" rel="noopener nofollow"></a></li>
                        <li><a class="anilist-icon" href="https://anilist.co/anime/141534" target="_blank" rel="noopener nofollow"></a></li>
                        <li><a class="mal-icon" href="https://myanimelist.net/anime/50360" target="_blank" rel="noopener nofollow"></a></li>
                        <li><a class="anisearch-icon" href="https://www.anisearch.com/anime/16785" target="_blank" rel="noopener nofollow"></a></li>
                    </ul>
                </div>
            </article>
        </main>
    HTML;

    public function test_crawl_all_animes_ova_page()
    {
        $crawler = new Crawler($this->dummyHtml);

        Goutte::shouldReceive('request')
            ->once()
            ->withAnyArgs()
            ->andReturn($crawler);

        /** @var AnimeOva $animeOva */
        $animeOva = $this->app->make(AnimeOva::class);
        $data = $animeOva->all();
        $anime = $data['animes'][0];

        $this->assertInstanceOf(AnimeOva::class, $anime);
        $this->assertSame(10886, $anime->id);
        $this->assertSame('Mushoku Tensei: Isekai Ittara Honki Dasu 2nd Cour Extra Edition', $anime->title);
        $this->assertSame('https://u.livechart.me/anime/10886/poster_image/f003d8256759bf415a18b4ce212b4ff0.webp?style=small&format=webp', $anime->image);
    }
}
