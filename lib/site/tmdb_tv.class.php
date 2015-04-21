<?php

/*
    The Movie Database Site Plugin
    Copyright (C) 2013 by Rodney Beck <denney@mantrasoftware.net>

    Created for Open Media Collectors Database
    Copyright (C) 2001,2013 by Jason Pell

    This program is free software; you can redistribute it and/or
    modify it under the terms of the GNU General Public License
    as published by the Free Software Foundation; either version 2
    of the License, or (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.

 */
include_once("./lib/SitePlugin.class.php");
include_once("kint-master/Kint.class.php");

class tmdb_tv extends SitePlugin {
    private $baseURL = 'http://api.themoviedb.org/3/';
    private $imgBase = '';
    private $imgResultsSize = '';
    private $apikey = '';

    function tmdb_tv($site_type) {
        parent::SitePlugin($site_type);

        $this->apikey = $this->_site_plugin_conf_r['tmdb_apikey'];

        $jsonData = json_decode($this->fetchURI($this->baseURL . 'configuration?api_key=' . $this->apikey), true);

        if (!is_null($jsonData)) {
            $this->imgBase = $jsonData['images']['base_url'];
            $this->imgResultsSize = $jsonData['images']['poster_sizes'][0];
            if (is_numeric($this->_site_plugin_conf_r['cover_width'])) $this->_site_plugin_conf_r['cover_width'] = 'w'.$this->_site_plugin_conf_r['cover_width'];
        }
    }

    function queryListing($page_no, $items_per_page, $offset, $s_item_type, $search_vars_r) {
        if ($this->apikey == '') return false;
        //$tmdb_id = (strlen($_GET['tmdb_id']) > 0) ? $_GET['tmdb_id'] : $search_vars_r['tmdb_id'];
        //if (isset($tmdb_id) && is_numeric($tmdb_id)) {
        if(strlen($search_vars_r['tmdb-tv_id'])) {
            $tvshowOverviewData = json_decode($this->fetchURI($this->baseURL . 'tv/' . $search_vars_r['tmdb-tv_id'] . '?api_key=' . $this->apikey), true);

            if (!is_null($tvshowOverviewData)) {
                $attributeArray = array('show_title' => $tvshowOverviewData['original_name'], 'show_overview' => $tvshowOverviewData['overview'], 'episode_runtime' => $tvshowOverviewData['episode_run_time'], 'production_companies' => $tvshowOverviewData['production_companies'], 'genres' => $tvshowOverviewData['genres'], 'original_lang' => $tvshowOverviewData['original_language']);
                if (isset($tvshowOverviewData['seasons']) && count($tvshowOverviewData['seasons'] > 0)) {
                    foreach ($tvshowOverviewData['seasons'] as $season) {
                        if ($result['poster_path'] == 'null') $result['poster_path'] = file_cache_get_noimage_r('listing');
                        $attributeArray['season'] = $season['season_number'];
                        $this->addListingRow($tvshowOverviewData['original_name'].': Season '.$season['season_number'], $this->imgBase . $this->imgResultsSize . $season['poster_path'], 'Season '.$season['season_number'].', '.$season['air_date'], array('tmdb-tv_id' => $tvshowOverviewData['id'], 'series_no' => $season['season_number']));
                    }
                } else {
                    $this->addListingRow(null, null, null, array('tmdb_id' => $search_vars_r['tmdb_id']));
                }

                if(!is_opendb_session_var('tmdb-tv-'.$tvshowOverviewData['id'])) {
                    foreach ($attributeArray as $key => $value) {
                        register_opendb_session_array_var('tmdb-tv-'.$tvshowOverviewData['id'], $key, $value);
                    }
                }

            return true;
            }
        } else {
            $release = (strlen($search_vars_r['year']) > 0) ? '&first_air_date_year=' . rawurlencode(strtolower($search_vars_r['year'])) : '';
            $tvshowOverviewData = json_decode($this->fetchURI($this->baseURL . 'search/tv?api_key=' . $this->apikey . $release . '&query=' . rawurlencode(strtolower($search_vars_r['series_title']))), true);
            if (!is_null($tvshowOverviewData)) {
                foreach ($tvshowOverviewData['results'] as $result) {
                    if ($result['poster_path'] == 'null') $result['poster_path'] = file_cache_get_noimage_r('listing');
                    $this->_http_vars['op'] = "site-search";
                    $this->addListingRow($result['original_name'], $this->imgBase . $this->imgResultsSize . $result['poster_path'], 'TV Show '.$result['first_air_date'], array('tmdb-tv_id' => $result['id']));
                }
                

                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * - TV Shows -
     * title            = Show name: Season name.
     * orig_title       = Original show name.
     * overview         = Show overview & Season overview.
     * runtime          = Episode runtime.
     * cover            = Show poster image URL.
     * pub_year         = First broadcast year of show.
     * collection       = Show name.
     *
     * genre            = Array of tv/movie genres.
     * prod_companies   = Array of tv production companies.
     * actors           = Array of tv actors.
     * directors        = Array of tv directors.
     * producers        = Array of tv producers.
     * music            = Array of music composers.
     * writers          = Array of tv writers.
     *
     */
    function queryItem($search_attributes_r, $s_item_type) {
        if ($this->apikey == '') return false;
        $tmdb_id = $search_attributes_r['tmdb-tv_id'];
        $tvshowOverviewData = (is_opendb_session_var('tmdb-tv-'.$tmdb_id)) ? get_opendb_session_var('tmdb-tv-'.$tmdb_id) : array();
        $tvshowSeasonData = json_decode($this->fetchURI($this->baseURL . 'tv/' . $tmdb_id . '/season/' . $search_attributes_r['series_no'] . '?api_key=' . $this->apikey . '&append_to_response=credits', true), true);
        if (!is_null($tvshowSeasonData)) {
            $seasonOverview = (strlen($tvshowSeasonData['overview']) > 0) ? PHP_EOL . PHP_EOL . '---------------' . PHP_EOL . PHP_EOL . $tvshowSeasonData['overview'] : '';
            $this->addItemAttribute('title', $tvshowOverviewData['show_title'] . ': ' . $tvshowSeasonData['name']);
            //$this->addItemAttribute('orig_name', $tvshowOverviewData['show_title'] . ': ' . $tvshowSeasonData['original_name']);
            $this->addItemAttribute('plot', $tvshowOverviewData['show_overview'] . $seasonOverview);
            $this->addItemAttribute('episode_runtime', $tvshowOverviewData['episode_runtime']);
            $this->addItemAttribute('cover', $this->imgBase . $this->_site_plugin_conf_r['cover_width'] . $tvshowSeasonData['poster_path']);
            $this->addItemAttribute('year', substr($tvshowSeasonData['air_date'], 0, 4));
            $this->addItemAttribute('collection', $tvshowOverviewData['show_title']);
            $this->addItemAttribute('original_language', $tvshowOverviewData['original_language']);

            $genres = array();
            foreach ($tvshowOverviewData['genres'] as $genre) {
                $genres[] = $genre['name'];
            }
            $this->addItemAttribute('genre', $genres);

            $prod_companies = array();
            if(isset($tvshowOverviewData['production_companies'])){
                foreach ($tvshowOverviewData['production_companies'] as $prod_company) {
                    $prod_companies[] = $prod_company['name'];
                }
            }
            $this->addItemAttribute('prod_companies', $prod_companies);

            $actors = array();
            foreach ($tvshowSeasonData['credits']['cast'] as $actor) {
                $actors[] = $actor['name'];
            }
            $this->addItemAttribute('actors', $actors);

            $directors = array();
            $producers = array();
            $music = array();
            $writers = array();
            $others = array();
            foreach ($tvshowSeasonData['credits']['crew'] as $crew) {
                switch ($crew['job']) {
                    case 'Director':
                        $directors[] = $crew['name'];
                        break;
                    case 'Producer':
                    case 'Executive Producer':
                        $producers[] = $crew['name'];
                        break;
                    case 'Music':
                    case 'Musical':
                    case 'Original Music Composer':
                        $music[] = $crew['name'];
                        break;
                    case 'Writer':
                    case 'Screenplay':
                    case 'Novel':
                        $writers[] = $crew['name'];
                        break;
                    default:
                        $others[] = $crew['job'];
                        break;
                }
            }
            $this->addItemAttribute('directors', $directors);
            $this->addItemAttribute('producers', $producers);
            $this->addItemAttribute('music', $music);
            $this->addItemAttribute('writers', $writers);
            $this->addItemAttribute('others', $others);

            $episodes = array();
            foreach ($tvshowSeasonData['episodes'] as $episode) {
                $episodes[] = $episode['name'];
            }
            $this->addItemAttribute('episodes', $episodes);

            return true;
        } else {
            return false;
        }
    }
}
