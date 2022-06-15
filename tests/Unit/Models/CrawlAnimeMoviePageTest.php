<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Facades\Goutte;
use Symfony\Component\DomCrawler\Crawler;
use App\Models\AnimeMovie;

class CrawlAnimeMoviePageTest extends TestCase
{
    private string $dummyHtml = <<<HTML
        <main class="chart" data-controller="anime-card-list">
            <article class="anime" data-anime-id="10488" data-romaji="Go-toubun no Hanayome Movie" data-english="The Quintessential Quintuplets the Movie" data-native="映画 五等分の花嫁" data-alternate="[&quot;Gotoubun no Hanayome Movie&quot;,&quot;The Five Equal Brides Movie&quot;,&quot;5-toubun no Hanayome Movie&quot;,&quot;The Five Wedded Brides Movie&quot;,&quot;Las quintiliizas, la película&quot;]" data-premiere="1652972400" data-premiere-precision="3" data-controller="anime-card" data-anime-card-list-target="card" data-library-status="">
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
                        <a data-anime-card-target="mainTitle" href="/anime/10488">Go-toubun no Hanayome Movie</a>
                    </h3>
                    <ol class="anime-tags">
                        <li><a href="/tags/5">Comedy</a></li>
                        <li><a href="/tags/15">Harem</a></li>
                        <li><a href="/tags/34">Romance</a></li>
                        <li><a href="/tags/36">School</a></li>
                        <li><a href="/tags/43">Shounen</a></li>
                    </ol>
                    <div class="poster-container">
                        <img class="lazy-img" width="175" height="250" src="https://u.livechart.me/anime/10488/poster_image/72eb3fed9216a360c3a7e17a4bdafe15.webp?style=small&amp;format=webp" alt="Go-toubun no Hanayome Movie" data-src="https://u.livechart.me/anime/10488/poster_image/72eb3fed9216a360c3a7e17a4bdafe15.webp?style=small&amp;format=jpg" data-srcset="https://u.livechart.me/anime/10488/poster_image/72eb3fed9216a360c3a7e17a4bdafe15.webp?style=small&amp;format=jpg 1x, https://u.livechart.me/anime/10488/poster_image/72eb3fed9216a360c3a7e17a4bdafe15.webp?style=large&amp;format=jpg 2x" data-anime-card-target="poster" data-lazy-loaded="true" srcset="https://u.livechart.me/anime/10488/poster_image/72eb3fed9216a360c3a7e17a4bdafe15.webp?style=small&amp;format=webp 1x, https://u.livechart.me/anime/10488/poster_image/72eb3fed9216a360c3a7e17a4bdafe15.webp?style=large&amp;format=webp 2x">
                        <div class="anime-extras">
                            <div class="anime-avg-user-rating" title="8.02 out of 10 based on 169 user ratings" data-action="click->anime-card#showLibraryEditor">
                                <i class="icon-star"></i>8.02 
                            </div>
                        </div>
                    </div>
                    <div class="anime-info">
                        <ul class="anime-studios">
                            <li><a href="/studios/259" data-anime-card-target="studioLink">Bibury Animation Studios</a></li>
                        </ul>
                        <div class="anime-date" data-action="click->anime-card#showPremiereDateTime">May 20, 2022 JST</div>
                        <div class="anime-metadata">
                            <div class="anime-source">Manga</div>
                            <div class="anime-episodes">2h 16m</div>
                        </div>
                        <div class="anime-synopsis" data-anime-card-target="synopsis">
                            <p class="editor-note">※ NOTE: Theatrical Premiere</p>
                            <p class="text-italic">No synopsis has been added to this title.</p>
                        </div>
                    </div>
                    <ul class="related-links">
                        <li><a class="website-icon" href="http://www.tbs.co.jp/anime/5hanayome/" target="_blank" rel="noopener nofollow"></a></li>
                        <li><a class="preview-icon" href="/anime/10488/videos" data-action="anime-card#showVideos"></a></li>
                        <li><a class="twitter-icon" href="https://twitter.com/5Hanayome_anime" target="_blank" rel="noopener nofollow"></a></li>
                        <li><a class="anilist-icon" href="https://anilist.co/anime/131520" target="_blank" rel="noopener nofollow"></a></li>
                        <li><a class="mal-icon" href="https://myanimelist.net/anime/48548" target="_blank" rel="noopener nofollow"></a></li>
                        <li><a class="anidb-icon" href="http://anidb.net/a16165" target="_blank" rel="noopener nofollow"></a></li>
                        <li><a class="anime-planet-icon" href="http://www.anime-planet.com/anime/the-quintessential-quintuplets-movie" target="_blank" rel="noopener nofollow"></a></li>
                        <li><a class="anisearch-icon" href="https://www.anisearch.com/anime/16091" target="_blank" rel="noopener nofollow"></a></li>
                        <li><a class="kitsu-icon" href="https://kitsu.io/anime/44229" target="_blank" rel="noopener nofollow"></a></li>
                    </ul>
                </div>
            </article>
        </main>
    HTML;

    public function test_crawl_all_animes_movie_page()
    {
        $crawler = new Crawler($this->dummyHtml);

        Goutte::shouldReceive('request')
            ->once()
            ->withAnyArgs()
            ->andReturn($crawler);

        /** @var  AnimeMovie $animeMovie */
        $animeMovie = $this->app->make(AnimeMovie::class);
        $data = $animeMovie->all();
        $anime = $data['animes'][0];

        $this->assertInstanceOf(AnimeMovie::class, $anime);
        $this->assertSame(10488, $anime->id);
        $this->assertSame('Go-toubun no Hanayome Movie', $anime->title);
        $this->assertSame('https://u.livechart.me/anime/10488/poster_image/72eb3fed9216a360c3a7e17a4bdafe15.webp?style=small&format=webp', $anime->image);
    }
}
