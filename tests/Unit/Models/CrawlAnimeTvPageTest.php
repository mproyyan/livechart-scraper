<?php

namespace Tests\Unit\Models;

use App\Facades\Goutte;
use Symfony\Component\DomCrawler\Crawler;
use Tests\TestCase;
use App\Models\AnimeTv;

class CrawlAnimeTvPageTest extends TestCase
{
    private string $dummyHtml = <<<HTML
        <main class="chart" data-controller="anime-card-list">
            <article class="anime" data-anime-id="10456" data-romaji="SPY x FAMILY" data-english="" data-native="SPY×FAMILY" data-alternate="[&quot;スパイファミリー&quot;,&quot;SxF&quot;,&quot;스파이 패밀리&quot;]" data-premiere="1649512800" data-premiere-precision="4" data-controller="anime-card" data-anime-card-list-target="card" data-library-status=""><div class="anime-card"><div class="anime-card--mark-menu" data-anime-card-target="marksMenu" data-action="mouseover->anime-card#showMarkMenu mouseout->anime-card#hideMarkMenu">
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
                        <a data-anime-card-target="mainTitle" href="/anime/10456">SPY x FAMILY</a>
                    </h3>
                    <ol class="anime-tags">
                        <li><a href="/tags/1">Action</a></li>
                        <li><a href="/tags/5">Comedy</a></li>
                        <li><a href="/tags/43">Shounen</a></li>
                    </ol>
                    <div class="poster-container">
                        <time class="episode-countdown" title="Click to view time/convert to another time zone" data-anime-card-target="countdown" data-action="click->anime-card#showEpisodeTime" data-timestamp="1655560800" data-label="EP11">EP11: 4d 13h 09m 36s</time>
                        <img class="lazy-img" width="175" height="250" src="https://u.livechart.me/anime/10456/poster_image/cdeaf17feb183f5d8ea30d23dce135a3.jpg?style=small&amp;format=webp" alt="SPY x FAMILY" data-src="https://u.livechart.me/anime/10456/poster_image/cdeaf17feb183f5d8ea30d23dce135a3.jpg?style=small&amp;format=jpg" data-srcset="https://u.livechart.me/anime/10456/poster_image/cdeaf17feb183f5d8ea30d23dce135a3.jpg?style=small&amp;format=jpg 1x, https://u.livechart.me/anime/10456/poster_image/cdeaf17feb183f5d8ea30d23dce135a3.jpg?style=large&amp;format=jpg 2x" data-anime-card-target="poster" data-lazy-loaded="true" srcset="https://u.livechart.me/anime/10456/poster_image/cdeaf17feb183f5d8ea30d23dce135a3.jpg?style=small&amp;format=webp 1x, https://u.livechart.me/anime/10456/poster_image/cdeaf17feb183f5d8ea30d23dce135a3.jpg?style=large&amp;format=webp 2x">
                        <div class="anime-extras">
                            <div class="anime-avg-user-rating" title="8.82 out of 10 based on 2,207 user ratings" data-action="click->anime-card#showLibraryEditor">
                                <i class="icon-star"></i>
                                8.82
                            </div>
                        </div>
                    </div>
                    <div class="anime-info">
                        <ul class="anime-studios">
                            <li><a href="/studios/135" data-anime-card-target="studioLink">WIT STUDIO</a></li>
                            <li><a href="/studios/296" data-anime-card-target="studioLink">CloverWorks</a></li>
                        </ul>
                        <div class="anime-date" data-action="click->anime-card#showPremiereDateTime">Apr 9, 2022 at 11:00pm JST</div>
                        <div class="anime-metadata">
                            <div class="anime-source">Manga</div>
                            <div class="anime-episodes">12 eps × 24m</div>
                        </div>
                        <div class="anime-synopsis" data-anime-card-target="synopsis">
                            <p class="editor-note">※ NOTE: Split 2-cour broadcast</p>
                            <p>Everyone has a part of themselves they cannot show to anyone else.</p>
                            <p>At a time when all nations of the world were involved in a fierce war of information happening behind closed doors, Ostania and Westalis had been in a state of cold war against one another for decades.</p>
                            <p>The Westalis Intelligence Services' Eastern-Focused Division (WISE) sends their most talented spy, "Twilight," on a top-secret mission to investigate the movements of Donovan Desmond, the chairman of Ostania's National Unity Party, who is threatening peace efforts between the two nations.</p>
                            <p>This mission is known as "Operation Strix."</p>
                            <p>It consists of "putting together a family in one week in order to infiltrate social gatherings organized by the elite school that Desmond's son attends."</p>
                            <p>"Twilight" takes on the identity of psychiatrist Loid Forger and starts looking for family members. But Anya, the daughter he adopts, turns out to have the ability to read people's minds, while his wife, Yor, is an assassin! With it being in each of their own interests to keep these facts hidden, they start living together while concealing their true identities from one another.</p>
                            <p>World peace is now in the hands of this brand-new family as they embark on an adventure full of surprises.</p><p class="text-italic">[Source: <a rel="nofollow noopener" target="_blank" href="https://www.crunchyroll.com/anime-news/2021/11/20-1/crunchyroll-announces-spy-x-family-and-more-at-anime-nyc">Crunchyroll</a>]</p>
                        </div>
                    </div>
                    <ul class="related-links">
                        <li><a class="website-icon" href="https://spy-family.net" target="_blank" rel="noopener nofollow"></a></li>
                        <li><a class="preview-icon" href="/anime/10456/videos" data-action="anime-card#showVideos"></a></li>
                        <li><a class="watch-icon" href="/anime/10456/streams" data-action="anime-card#showStreams"></a></li>
                        <li><a class="twitter-icon" href="https://twitter.com/spyfamily_anime" target="_blank" rel="noopener nofollow"></a></li>
                        <li><a class="anilist-icon" href="https://anilist.co/anime/140960" target="_blank" rel="noopener nofollow"></a></li>
                        <li><a class="mal-icon" href="https://myanimelist.net/anime/50265" target="_blank" rel="noopener nofollow"></a></li>
                        <li><a class="anidb-icon" href="http://anidb.net/a16947" target="_blank" rel="noopener nofollow"></a></li>
                        <li><a class="anime-planet-icon" href="http://www.anime-planet.com/anime/spy-x-family" target="_blank" rel="noopener nofollow"></a></li>
                        <li><a class="anisearch-icon" href="https://www.anisearch.com/anime/16717" target="_blank" rel="noopener nofollow"></a></li>
                        <li><a class="kitsu-icon" href="https://kitsu.io/anime/45398" target="_blank" rel="noopener nofollow"></a></li>
                        <li><a class="crunchyroll-icon" href="http://www.crunchyroll.com/series/G4PH0WXVJ" target="_blank" rel="noopener nofollow"></a></li>
                    </ul>
                </div>
            </arcticle>
        </main>
    HTML;

    public function test_crawl_all_animes_tv_page()
    {
        $crawler = new Crawler($this->dummyHtml);

        Goutte::shouldReceive('request')
            ->once()
            ->withAnyArgs()
            ->andReturn($crawler);

        /** @var  AnimeTv $animeTv */
        $animeTv = $this->app->make(AnimeTv::class);
        $data = $animeTv->all();
        $anime = $data['animes'][0];

        $this->assertInstanceOf(AnimeTv::class, $animeTv);
        $this->assertSame(10456, $anime->id);
        $this->assertSame('SPY x FAMILY', $anime->title);
        $this->assertSame('https://u.livechart.me/anime/10456/poster_image/cdeaf17feb183f5d8ea30d23dce135a3.jpg?style=small&format=webp', $anime->image);
    }
}
