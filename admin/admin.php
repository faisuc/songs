<h1>Songs</h1>

<table border=1>
	<thead>
		<tr>
			<th>ID</th>
			<th><a href="<?php echo parse_url($url , PHP_URL_QUERY) ? $url . '&table=SongPosition&sort=' . $col2Sorting : $url . '?table=SongPosition&sort=' . $col2Sorting; ?>">Position</a></th>
			<th><a href="<?php echo parse_url($url , PHP_URL_QUERY) ? $url . '&table=SongTitle&sort=' . $col3Sorting : $url . '?table=SongTitle&sort=' . $col3Sorting; ?>">Title</a></th>
			<th><a href="<?php echo parse_url($url , PHP_URL_QUERY) ? $url . '&table=SongArtist&sort=' . $col4Sorting : $url . '?table=SongArtist&sort=' . $col4Sorting; ?>">Artist</a></th>
			<th><a href="<?php echo parse_url($url , PHP_URL_QUERY) ? $url . '&table=SongAlbum&sort=' . $col5Sorting : $url . '?table=SongAlbum&sort=' . $col5Sorting; ?>">Album</a></th>
			<th><a href="<?php echo parse_url($url , PHP_URL_QUERY) ? $url . '&table=SongGenre&sort=' . $col6Sorting : $url . '?table=SongGenre&sort=' . $col6Sorting; ?>">Genre</a></th>
			<th><a href="<?php echo parse_url($url , PHP_URL_QUERY) ? $url . '&table=SongLyrics&sort=' . $col7Sorting : $url . '?table=SongLyrics&sort=' . $col7Sorting; ?>">Lyrics</a></th>
			<th><a href="<?php echo parse_url($url , PHP_URL_QUERY) ? $url . '&table=SongUrl&sort=' . $col8Sorting : $url . '?table=SongUrl&sort=' . $col8Sorting; ?>">Url</a></th>
		</tr>
	</thead>
	<tbody>
		<?php
			foreach ($results as $song)
			{
		?>
				<tr>
					<td><?php echo $song->SongID; ?></td>
					<td><?php echo $song->SongPosition; ?></td>
					<td><?php echo $song->SongTitle; ?></td>
					<td><?php echo $song->SongArtist; ?></td>
					<td><?php echo $song->SongAlbum; ?></td>
					<td><?php echo $song->SongGenre; ?></td>
					<td><?php echo $song->SongLyrics; ?></td>
					<td><?php echo $song->SongUrl; ?></td>
				</tr>
		<?php
			}			
		?>
	</tbody>
</table>
<?php

	if($totalPage > 1){

		$customPaginateHTML =  '<div><span>Page '.$page.' of '.$totalPage.'</span><br />'.paginate_links( array(
			'base' => add_query_arg( 'cpage', '%#%' ),
			'format' => '',
			'prev_text' => __('&laquo;'),
			'next_text' => __('&raquo;'),
			'total' => $totalPage,
			'current' => $page
		)).'</div>';

		echo $customPaginateHTML;
	}

?>