#########################################################
# OpenDb 1.5 The Movie Database (tmdb) Site Plugin
#########################################################

#
# Site Plugin.
#
INSERT INTO s_site_plugin (site_type, classname, title, image, description, external_url, items_per_page, more_info_url)
    VALUES ('tmdb-tv', 'tmdb_tv', 'TheMovieDB-TV', 'tmdb.png', 'TheMovieDB TV Shows', 'https://www.themoviedb.org/tv', 50, 'https://www.themoviedb.org/tv/{tmdb-tv_id}');

#
# Site Plugin Configuration
#

INSERT INTO s_site_plugin_conf ( site_type, name, keyid, description, value )
    VALUES ( 'tmdb-tv', 'tmdb_apikey', '0', 'TheMovieDB API Key', '' );
INSERT INTO s_site_plugin_conf ( site_type, name, keyid, description, value )
  VALUES ( 'tmdb-tv', 'cover_width', '0', 'Cover Image Width', 'original' );

#
# Site Plugin Input Fields
#
INSERT INTO s_site_plugin_input_field (site_type, field, order_no, description, prompt, field_type, default_value, refresh_mask)
    VALUES ('tmdb-tv', 'series_title', 1, '', 'Series/Season Title', 'text', '', '{series_title}');
INSERT INTO s_site_plugin_input_field (site_type, field, order_no, description, prompt, field_type, default_value, refresh_mask)
    VALUES ('tmdb-tv', 'series_no', 2, '', 'Series/Season Number', 'text', '', '{season_number}');
INSERT INTO s_site_plugin_input_field (site_type, field, order_no, description, prompt, field_type, default_value, refresh_mask)
    VALUES ('tmdb-tv', 'year', 3, '', 'Year of First Broadcast', 'text', '', '{year}');
INSERT INTO s_site_plugin_input_field (site_type, field, order_no, description, prompt, field_type, default_value, refresh_mask)
    VALUES ('tmdb-tv', 'tmdb-tv_id', 4, '', 'TheMovieDB ID', 'text', '', '{tmdb-tv_id}');

#
# Site Plugin Links
#
INSERT INTO s_site_plugin_link (site_type, s_item_type_group, s_item_type, order_no, description, url, title_url)
    VALUES ('tmdb-tv', '*', '*', 1, 'More Info', 'http://www.themoviedb.org/tv/{tmdb-tv_id}', '');

#
# Site Plugin System Attribute Type Map
#
INSERT INTO s_site_plugin_s_attribute_type_map (site_type, s_item_type_group, s_item_type, variable, s_attribute_type, lookup_attribute_val_restrict_ind)
    VALUES ('tmdb-tv', '*', '*', 'orig_name', '', 'N');
INSERT INTO s_site_plugin_s_attribute_type_map (site_type, s_item_type_group, s_item_type, variable, s_attribute_type, lookup_attribute_val_restrict_ind)
    VALUES ('tmdb-tv', '*', '*', 'plot', '', 'N');
INSERT INTO s_site_plugin_s_attribute_type_map (site_type, s_item_type_group, s_item_type, variable, s_attribute_type, lookup_attribute_val_restrict_ind)
    VALUES ('tmdb-tv', '*', '*', 'original_language', '', 'N');
INSERT INTO s_site_plugin_s_attribute_type_map (site_type, s_item_type_group, s_item_type, variable, s_attribute_type, lookup_attribute_val_restrict_ind)
    VALUES ('tmdb-tv', '*', '*', 'episode_runtime', '', 'N');
INSERT INTO s_site_plugin_s_attribute_type_map (site_type, s_item_type_group, s_item_type, variable, s_attribute_type, lookup_attribute_val_restrict_ind)
    VALUES ('tmdb-tv', '*', '*', 'cover', '', 'N');
INSERT INTO s_site_plugin_s_attribute_type_map (site_type, s_item_type_group, s_item_type, variable, s_attribute_type, lookup_attribute_val_restrict_ind)
    VALUES ('tmdb-tv', '*', '*', 'imdb_id', '', 'N');
INSERT INTO s_site_plugin_s_attribute_type_map (site_type, s_item_type_group, s_item_type, variable, s_attribute_type, lookup_attribute_val_restrict_ind)
    VALUES ('tmdb-tv', '*', '*', 'year', '', 'N');
INSERT INTO s_site_plugin_s_attribute_type_map (site_type, s_item_type_group, s_item_type, variable, s_attribute_type, lookup_attribute_val_restrict_ind)
    VALUES ('tmdb-tv', '*', '*', 'genre', '', 'N');
INSERT INTO s_site_plugin_s_attribute_type_map (site_type, s_item_type_group, s_item_type, variable, s_attribute_type, lookup_attribute_val_restrict_ind)
    VALUES ('tmdb-tv', '*', '*', 'prod_companies', '', 'N');
INSERT INTO s_site_plugin_s_attribute_type_map (site_type, s_item_type_group, s_item_type, variable, s_attribute_type, lookup_attribute_val_restrict_ind)
    VALUES ('tmdb-tv', '*', '*', 'actors', '', 'N');
INSERT INTO s_site_plugin_s_attribute_type_map (site_type, s_item_type_group, s_item_type, variable, s_attribute_type, lookup_attribute_val_restrict_ind)
    VALUES ('tmdb-tv', '*', '*', 'directors', '', 'N');
INSERT INTO s_site_plugin_s_attribute_type_map (site_type, s_item_type_group, s_item_type, variable, s_attribute_type, lookup_attribute_val_restrict_ind)
    VALUES ('tmdb-tv', '*', '*', 'producers', '', 'N');
INSERT INTO s_site_plugin_s_attribute_type_map (site_type, s_item_type_group, s_item_type, variable, s_attribute_type, lookup_attribute_val_restrict_ind)
    VALUES ('tmdb-tv', '*', '*', 'music', '', 'N');
INSERT INTO s_site_plugin_s_attribute_type_map (site_type, s_item_type_group, s_item_type, variable, s_attribute_type, lookup_attribute_val_restrict_ind)
    VALUES ('tmdb-tv', '*', '*', 'writers', '', 'N');
INSERT INTO s_site_plugin_s_attribute_type_map (site_type, s_item_type_group, s_item_type, variable, s_attribute_type, lookup_attribute_val_restrict_ind)
    VALUES ('tmdb-tv', '*', '*', 'collection', '', 'N');

#
# Site Plugin Attribute Type(s)
#
INSERT INTO s_attribute_type (s_attribute_type, description, prompt, input_type, display_type, s_field_type, site_type)
    VALUES ('TMDB-TV_ID', 'TheMovieDB ID', 'TheMovieDB ID', 'hidden', 'hidden', '', 'tmdb-tv');
