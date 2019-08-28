<?php
    $root = dirname(dirname(dirname(dirname(dirname(__FILE__)))))."/config.php";
    require_once "$root";
	// General
	$moxieManagerConfig['general.license'] = 'P54A-7LJH-ESN4-MEKR-3NAH-UC6X-2DUX-BLYT';
	$moxieManagerConfig['general.hidden_tools'] = '';
	$moxieManagerConfig['general.disabled_tools'] = '';
	$moxieManagerConfig['general.plugins'] = 'AutoFormat,AutoRename,Favorites,Ftp,History,Uploaded';
	$moxieManagerConfig['general.demo'] = false;
	$moxieManagerConfig['general.debug'] = true;
	$moxieManagerConfig['general.language'] = 'vi';
	$moxieManagerConfig['general.temp_dir'] = MEDIA_PATH;
	$moxieManagerConfig['general.allow_override'] = 'hidden_tools,disabled_tools';

	// Filesystem
	$moxieManagerConfig['filesystem.rootpath'] = '';
	$moxieManagerConfig['filesystem.include_directory_pattern'] = '';
	$moxieManagerConfig['filesystem.exclude_directory_pattern'] = '/^mcith$/i';
	$moxieManagerConfig['filesystem.include_file_pattern'] = '';
	$moxieManagerConfig['filesystem.exclude_file_pattern'] = '';
	$moxieManagerConfig['filesystem.extensions'] = 'jpg,jpeg,png,ico, gif,html,htm,txt,docx,doc,pdf,mp3,mp4,flv,xls,xlxs';
	$moxieManagerConfig['filesystem.readable'] = true;
	$moxieManagerConfig['filesystem.writable'] = true;
	$moxieManagerConfig['filesystem.directories'] = array(
		/*
		"images" => array(
			"upload.extensions" => "gif,jpg,png"
		)
		*/
	);
	$moxieManagerConfig['filesystem.allow_override'] = '*';

	// Createdir
	$moxieManagerConfig['createdir.templates'] = '';
	$moxieManagerConfig['createdir.include_directory_pattern'] = '';
	$moxieManagerConfig['createdir.exclude_directory_pattern'] = '';
	$moxieManagerConfig['createdir.allow_override'] = '*';

	// Createdoc
	$moxieManagerConfig['createdoc.templates'] = '';
	$moxieManagerConfig['createdoc.fields'] = 'Document title=title';
	$moxieManagerConfig['createdoc.include_file_pattern'] = '';
	$moxieManagerConfig['createdoc.exclude_file_pattern'] = '';
	$moxieManagerConfig['createdoc.extensions'] = '*';
	$moxieManagerConfig['createdoc.allow_override'] = '*';

	// Upload
	$moxieManagerConfig['upload.include_file_pattern'] = '';
	$moxieManagerConfig['upload.exclude_file_pattern'] = '';
	$moxieManagerConfig['upload.extensions'] = '*';
	$moxieManagerConfig['upload.maxsize'] = '10MB';
	$moxieManagerConfig['upload.overwrite'] = false;
	$moxieManagerConfig['upload.autoresize'] = true;
	$moxieManagerConfig['upload.autoresize_jpeg_quality'] = 100;
	$moxieManagerConfig['upload.max_width'] = 1920;
	$moxieManagerConfig['upload.max_height'] = 1080;
	$moxieManagerConfig['upload.chunk_size'] = '10MB';
	$moxieManagerConfig['upload.allow_override'] = '*';

	// Delete
	$moxieManagerConfig['delete.include_file_pattern'] = '';
	$moxieManagerConfig['delete.exclude_file_pattern'] = '';
	$moxieManagerConfig['delete.include_directory_pattern'] = '';
	$moxieManagerConfig['delete.exclude_directory_pattern'] = '';
	$moxieManagerConfig['delete.extensions'] = '*';
	$moxieManagerConfig['delete.allow_override'] = '*';

	// Rename
	$moxieManagerConfig['rename.include_file_pattern'] = '';
	$moxieManagerConfig['rename.exclude_file_pattern'] = '';
	$moxieManagerConfig['rename.include_directory_pattern'] = '';
	$moxieManagerConfig['rename.exclude_directory_pattern'] = '';
	$moxieManagerConfig['rename.extensions'] = '*';
	$moxieManagerConfig['rename.allow_override'] = '*';

	// Edit
	$moxieManagerConfig['edit.include_file_pattern'] = '';
	$moxieManagerConfig['edit.exclude_file_pattern'] = '';
	$moxieManagerConfig['edit.extensions'] = 'jpg,jpeg,png,gif,html,htm,txt';
	$moxieManagerConfig['edit.jpeg_quality'] = 90;
	$moxieManagerConfig['edit.line_endings'] = 'crlf';
	$moxieManagerConfig['edit.encoding'] = 'iso-8859-1';
	$moxieManagerConfig['edit.allow_override'] = '*';

	// View
	$moxieManagerConfig['view.include_file_pattern'] = '';
	$moxieManagerConfig['view.exclude_file_pattern'] = '';
	$moxieManagerConfig['view.extensions'] = 'jpg,jpeg,png,gif,html,htm,txt,pdf';
	$moxieManagerConfig['view.allow_override'] = '*';

	// Download
	$moxieManagerConfig['download.include_file_pattern'] = '';
	$moxieManagerConfig['download.exclude_file_pattern'] = '';
	$moxieManagerConfig['download.extensions'] = '*';
	$moxieManagerConfig['download.allow_override'] = '*';

	// Thumbnail
	$moxieManagerConfig['thumbnail.enabled'] = true;
	$moxieManagerConfig['thumbnail.auto_generate'] = true;
	$moxieManagerConfig['thumbnail.use_exif'] = true;
	$moxieManagerConfig['thumbnail.width'] = 200;
	$moxieManagerConfig['thumbnail.height'] = 200;
	$moxieManagerConfig['thumbnail.mode'] = "resize";
	$moxieManagerConfig['thumbnail.folder'] = 'thumb';
	$moxieManagerConfig['thumbnail.prefix'] = 'thumb_';
	$moxieManagerConfig['thumbnail.delete'] = true;
	$moxieManagerConfig['thumbnail.jpeg_quality'] = 75;
	$moxieManagerConfig['thumbnail.allow_override'] = '*';

	// Authentication
	$moxieManagerConfig['authenticator'] = 'CodeIgniterAuthenticator';
	$moxieManagerConfig['authenticator.login_page'] = BASE_ADMIN_URL.'auth/login';

	// SessionAuthenticator
	$moxieManagerConfig['SessionAuthenticator.logged_in_key'] = 'MyLoggedInKey';
	$moxieManagerConfig['SessionAuthenticator.user_key'] = 'user';
	$moxieManagerConfig['SessionAuthenticator.config_prefix'] = 'moxiemanager';

    // BasicAuthenticator
    $moxieManagerConfig['basicauthenticator.users'] = array(
        array("username" => "admin", "password" => "admin", "groups" => array("administrator"))
    );

    //CodeIgniterAuthenticator
    $moxieManagerConfig['CodeIgniterAuthenticator.environment'] = "production";
    $moxieManagerConfig['CodeIgniterAuthenticator.logged_in_key'] = "MyLoggedInKey";
    $moxieManagerConfig['CodeIgniterAuthenticator.user_key'] = 'user';
    // User specific path in moxiemanager/config.php
    //$moxieManagerConfig['filesystem.rootpath'] = 'users/${user}';

	// IpAuthenticator
	$moxieManagerConfig['IpAuthenticator.ip_numbers'] = '127.0.0.1';

	// ExternalAuthenticator
	$moxieManagerConfig['ExternalAuthenticator.external_auth_url'] = '';
	$moxieManagerConfig['ExternalAuthenticator.secret_key'] = '';
	$moxieManagerConfig['ExternalAuthenticator.basic_auth_user'] = '';
	$moxieManagerConfig['ExternalAuthenticator.basic_auth_password'] = '';

	// Local filesystem
	$moxieManagerConfig['filesystem.local.wwwroot'] = '';
	$moxieManagerConfig['filesystem.local.urlprefix'] = '';
	$moxieManagerConfig['filesystem.local.urlsuffix'] = '';
	$moxieManagerConfig['filesystem.local.access_file_name'] = 'mc_access';
	$moxieManagerConfig['filesystem.local.cache'] = false;
	$moxieManagerConfig['filesystem.local.allow_override'] = '*';

	// Log
	$moxieManagerConfig['log.enabled'] = true;
	$moxieManagerConfig['log.level'] = 'error';
	$moxieManagerConfig['log.path'] = 'data/logs';
	$moxieManagerConfig['log.filename'] = '{level}.log';
	$moxieManagerConfig['log.format'] = '[{time}] [{level}] {message}';
	$moxieManagerConfig['log.max_size'] = '100k';
	$moxieManagerConfig['log.max_files'] = '10';
	$moxieManagerConfig['log.filter'] = '';

	// Cache
	$moxieManagerConfig['cache.connection'] = "sqlite:./data/storage/cache.s3db";

	// Storage
	$moxieManagerConfig['storage.engine'] = 'json';
	$moxieManagerConfig['storage.path'] = './data/storage';

	// AutoFormat
	$moxieManagerConfig['autoformat.rules'] = '';
	$moxieManagerConfig['autoformat.jpeg_quality'] = 90;
	$moxieManagerConfig['autoformat.delete_format_images'] = true;

	// AutoRename
	$moxieManagerConfig['autorename.enabled'] = true;
	$moxieManagerConfig['autorename.spacechar'] = "-";
	$moxieManagerConfig['autorename.lowercase'] = true;
    $moxieManagerConfig['autorename.pattern'] = '[^0-9a-z-_]';

	// GoogleDrive
	$moxieManagerConfig['googledrive.client_id'] = '';

	// DropBox
	$moxieManagerConfig['dropbox.app_id'] = '';

	// Ftp
	$moxieManagerConfig['ftp.accounts'] = array(

		'ftpname' => array(
			'host' => '',
			'user' => '',
			'password' => '',
			'rootpath' => '/',
			'wwwroot' => '/',
			'passive' => true
		)

	);

	// Favorites
	$moxieManagerConfig['favorites.max'] = 100;

	// History
	$moxieManagerConfig['history.max'] = 100;
?>