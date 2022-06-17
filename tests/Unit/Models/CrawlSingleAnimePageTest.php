<?php

namespace Tests\Unit\Models;

use App\Facades\Goutte;
use Tests\TestCase;
use Symfony\Component\DomCrawler\Crawler;
use App\Models\AnimeDetail;

class CrawlSingleAnimePageTest extends TestCase
{
    private $dummyHtml = <<<HTML
        <div id="content" class="off-canvas-content" data-application-target="content">
            <div class="row" data-controller="anime-details" data-anime-details-id="2" data-library-status="">
                <div class="column medium-3 large-2 large-push-1">
                    <div class="anime-poster">
                        <img class="lazyload" width="175" height="250" src="https://u.livechart.me/anime/2/poster_image/9e833f7b109b02a2da5bae94476b4cf6.png?style=small&amp;format=webp" alt="Watashi ga Motenai no wa Dou Kangaete mo Omaera ga Warui!" data-src="https://u.livechart.me/anime/2/poster_image/9e833f7b109b02a2da5bae94476b4cf6.png?style=small&amp;format=jpg" data-srcset="https://u.livechart.me/anime/2/poster_image/9e833f7b109b02a2da5bae94476b4cf6.png?style=small&amp;format=jpg 1x, https://u.livechart.me/anime/2/poster_image/9e833f7b109b02a2da5bae94476b4cf6.png?style=large&amp;format=jpg 2x" data-controller="lazy-image" data-lazy-loaded="true" srcset="https://u.livechart.me/anime/2/poster_image/9e833f7b109b02a2da5bae94476b4cf6.png?style=small&amp;format=webp 1x, https://u.livechart.me/anime/2/poster_image/9e833f7b109b02a2da5bae94476b4cf6.png?style=large&amp;format=webp 2x">
                    </div>
                    <div class="button expanded library-entry-button with-icon" data-library-status="" data-message-default="My list" data-message-completed="Completed" data-message-watching="Watching" data-message-considering="Considering" data-message-skipping="Skipping" data-anime-details-target="libraryEditorButton" data-action="click->anime-details#showLibraryEditor">
                        <div class="grid-x align-center">
                            <div class="mark-icon" data-controller="mark-icon" data-anime-details-target="markIcon" data-mark-icon-viewer-status-value="null">
                                <svg viewBox="0 0 24 24" data-mark-icon-target="canvas"><use class="primary-path" data-mark-icon-target="primaryPath" href="#mark:none"></use></svg>
                            </div>
                            <span class="content">My list</span>
                        </div>
                    </div>
                    <div class="text-center">
                        <fieldset class="fieldset-box rating-box" title="7.09 out of 10 based on 1,104 user ratings"><legend class="fieldset-box--legend">Community rating</legend><span class="rating-box--rating">7.09</span><div class="rating-box--sample-size">1,104 ratings</div></fieldset>
                    </div>
                    <div class="section-heading">Original title</div>
                    <div class="section-body">
                        <small>私がモテないのはどう考えてもお前らが悪い!</small>
                    </div>
                    <div class="section-heading">Premiere</div>
                    <div class="section-body">
                        <small><a href="/summer-2013/tv">July 9, 2013</a></small>
                    </div>
                </div>
                <div class="column medium-9 large-8 large-pull-1">
                    <h4>Watashi ga Motenai no wa Dou Kangaete mo Omaera ga Warui! <div>
                        <small><i>WATAMOTE: No Matter How I Look at It, It’s You Guys Fault I’m Not Popular!</i></small></div>
                    </h4>
                    <div class="callout">
                        <div class="info-bar anime-meta-bar">
                            <div class="info-bar-cell">
                                <div class="info-bar-cell-label text-secondary">Format</div>
                                <div class="info-bar-cell-value">TV</div>
                            </div>
                            <div class="info-bar-cell">
                                <div class="info-bar-cell-label text-secondary">Source</div>
                                <div class="info-bar-cell-value">Manga</div>
                            </div>
                            <div class="info-bar-cell">
                                <div class="info-bar-cell-label text-secondary">Episodes</div>
                                <div class="info-bar-cell-value">12</div>
                            </div>
                            <div class="info-bar-cell">
                                <div class="info-bar-cell-label text-secondary">Run time</div>
                                <div class="info-bar-cell-value">24m</div>
                            </div>
                        </div>
                        <hr>
                        <div class="expandable-text expandable-text-collapsed" data-controller="expander" data-expander-clamp-height="120" data-expander-threshold="24">
                            <div class="expandable-text-body" data-controller="spoilable-text" data-action="click->spoilable-text#revealText" data-expander-target="expandable" data-anime-details-target="spoilableText" style="height: 120px;">
                                <p>At the tender age of 15, Kuroki Tomoko has already dated dozens and dozens of boys and she's easily the most popular girl around! The only problem is that absolutely none of that is real, and her perfect world exists only via dating games and romance shows. In fact, the sad truth is that she gets tongue tied just talking to people, and throughout middle school she's only had one actual friend. All of which makes Kuroki's entrance into the social pressure cooker of high school a new and special kind of hell. Because while Kuroki desperately wants to be popular, she's actually worse off than she would be if she was completely clueless as to how to go about it. After all, the things that work in "otome" games rarely play out the same way in reality, especially when the self-appointed "leading lady" isn't the paragon she thinks she is. There's not much gain and plenty of pain ahead, but even if it happens again and again, there's always someone else to blame in WATAMOTE ~ No Matter How I Look at It, It's You Guys' Fault I'm Not Popular!</p>
                            </div>
                            <div class="text-center">
                                <button class="button clear small" data-expander-target="showMoreButton" data-action="click->expander#expand">SHOW MORE</button>
                                <button class="hide button clear small" data-expander-target="showLessButton" data-action="click->expander#collapse">SHOW LESS</button>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="column medium-6">
                                <section>
                                    <div class="section-heading">Tags</div>
                                    <div class="inline-list">
                                        <li><a href="/tags/5">Comedy</a></li>
                                        <li><a href="/tags/36">School</a></li>
                                        <li><a href="/tags/43">Shounen</a></li>
                                        <li><a href="/tags/45">Slice of Life</a></li>
                                    </div>
                                </section>
                            </div>
                            <div class="column medium-6">
                                <section>
                                    <div class="section-heading">Studio</div>
                                    <div class="inline-list">
                                        <li><a href="/studios/97">SILVER LINK.</a></li>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    HTML;

    private $dataAnimeTesting = [
        'id' => 2,
        'title' => 'Watashi ga Motenai no wa Dou Kangaete mo Omaera ga Warui!',
        'image' => 'https://u.livechart.me/anime/2/poster_image/9e833f7b109b02a2da5bae94476b4cf6.png?style=small&format=webp',
        'synopsis' => "At the tender age of 15, Kuroki Tomoko has already dated dozens and dozens of boys and she's easily the most popular girl around! The only problem is that absolutely none of that is real, and her perfect world exists only via dating games and romance shows. In fact, the sad truth is that she gets tongue tied just talking to people, and throughout middle school she's only had one actual friend. All of which makes Kuroki's entrance into the social pressure cooker of high school a new and special kind of hell. Because while Kuroki desperately wants to be popular, she's actually worse off than she would be if she was completely clueless as to how to go about it. After all, the things that work in \"otome\" games rarely play out the same way in reality, especially when the self-appointed \"leading lady\" isn't the paragon she thinks she is. There's not much gain and plenty of pain ahead, but even if it happens again and again, there's always someone else to blame in WATAMOTE ~ No Matter How I Look at It, It's You Guys' Fault I'm Not Popular!",
        'formatted_synopsis' => [
            "At the tender age of 15, Kuroki Tomoko has already dated dozens and dozens of boys and she's easily the most popular girl around! The only problem is that absolutely none of that is real, and her perfect world exists only via dating games and romance shows. In fact, the sad truth is that she gets tongue tied just talking to people, and throughout middle school she's only had one actual friend. All of which makes Kuroki's entrance into the social pressure cooker of high school a new and special kind of hell. Because while Kuroki desperately wants to be popular, she's actually worse off than she would be if she was completely clueless as to how to go about it. After all, the things that work in \"otome\" games rarely play out the same way in reality, especially when the self-appointed \"leading lady\" isn't the paragon she thinks she is. There's not much gain and plenty of pain ahead, but even if it happens again and again, there's always someone else to blame in WATAMOTE ~ No Matter How I Look at It, It's You Guys' Fault I'm Not Popular!"
        ],
        'genres' => [
            [
                'genre_id' => 5,
                'genre' => 'Comedy'
            ],
            [
                'genre_id' => 36,
                'genre' => 'School'
            ],
            [
                'genre_id' => 43,
                'genre' => 'Shounen'
            ],
            [
                'genre_id' => 45,
                'genre' => 'Slice of Life'
            ]
        ],
        'type' => 'TV',
        'source' => 'Manga',
        'episodes' => 12,
        'duration' => [
            'hours' => 0,
            'minutes' => 24,
            'seconds' => 0
        ],
        'aired' => [
            'premiere' => 'Summer 2013',
            'props' => [
                'day' => 9,
                'month' => 7,
                'year' => 2013
            ]
        ],
        'season' => 'Summer',
        'year' => 2013,
        'studios' => [
            'SILVER LINK.'
        ]
    ];

    public function test_crawl_single_anime_page()
    {
        $crawler = new Crawler($this->dummyHtml);

        Goutte::shouldReceive('request')
            ->once()
            ->withAnyArgs()
            ->andReturn($crawler);

        /** @var AnimeDetail $animeDetail */
        $animeDetail = $this->app->make(AnimeDetail::class);
        $anime = $animeDetail->find(2);

        $this->assertInstanceOf(AnimeDetail::class, $anime);
        $this->assertSame($this->dataAnimeTesting['id'], $anime->id);
        $this->assertSame($this->dataAnimeTesting['title'], $anime->title);
        $this->assertSame($this->dataAnimeTesting['image'], $anime->image);
        $this->assertSame($this->dataAnimeTesting['synopsis'], $anime->synopsis);

        for ($i = 0; $i < count($this->dataAnimeTesting['formatted_synopsis']); $i++) {
            $this->assertSame($this->dataAnimeTesting['formatted_synopsis'][$i], $anime->formatted_synopsis[$i]);
        }

        for ($i = 0; $i < count($this->dataAnimeTesting['genres']); $i++) {
            $this->assertSame($this->dataAnimeTesting['genres'][$i]['genre_id'], $anime->genres[$i]['genre_id']);
            $this->assertSame($this->dataAnimeTesting['genres'][$i]['genre'], $anime->genres[$i]['genre']);
        }

        $this->assertSame($this->dataAnimeTesting['type'], $anime->type);
        $this->assertSame($this->dataAnimeTesting['source'], $anime->source);
        $this->assertSame($this->dataAnimeTesting['episodes'], $anime->episodes);
        $this->assertSame($this->dataAnimeTesting['duration']['hours'], $anime->duration['hours']);
        $this->assertSame($this->dataAnimeTesting['duration']['minutes'], $anime->duration['minutes']);
        $this->assertSame($this->dataAnimeTesting['duration']['seconds'], $anime->duration['seconds']);
        $this->assertSame($this->dataAnimeTesting['aired']['premiere'], $anime->aired['premiere']);
        $this->assertSame($this->dataAnimeTesting['aired']['props']['day'], $anime->aired['props']['day']);
        $this->assertSame($this->dataAnimeTesting['aired']['props']['month'], $anime->aired['props']['month']);
        $this->assertSame($this->dataAnimeTesting['aired']['props']['year'], $anime->aired['props']['year']);
        $this->assertSame($this->dataAnimeTesting['season'], $anime->season);
        $this->assertSame($this->dataAnimeTesting['year'], $anime->year);

        for ($i = 0; $i < count($this->dataAnimeTesting['studios']); $i++) {
            $this->assertSame($this->dataAnimeTesting['studios'][$i], $anime->studios[$i]);
        }
    }
}
