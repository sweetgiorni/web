<?
$artworkId = HttpInput::Int(GET, 'artworkid');

try{
	$artwork = Artwork::Get($artworkId);
	$existingArtwork = Artwork::GetByUrlPath($artwork->Artist->UrlName, $artwork->UrlName);
}
catch(Exceptions\AppException){
	Template::Emit404();
}

?><?= Template::Header(['title' => 'Review Artwork', 'artwork' => true, 'highlight' => '', 'description' => 'Unverified artwork.']) ?>
<main class="artworks">
	<section class="narrow">
		<?= Template::ArtworkDetail(['artwork' => $artwork]) ?>
	</section>
	<h2>Review</h2>
	<? if($existingArtwork === null){ ?>
	<p>Review the metadata and PD proof for this artwork submission. Approve to make it available for future producers.</p>
	<? }elseif($existingArtwork->ArtworkId == $artwork->ArtworkId){ ?>
	<p><? if($existingArtwork->Status == COVER_ARTWORK_STATUS_APPROVED){ ?><a href="<?= $existingArtwork->Url ?>">This artwork</a> is already approved. <? }elseif($existingArtwork->Status == COVER_ARTWORK_STATUS_IN_USE){ ?><a href="<?= $existingArtwork->Url ?>">This artwork</a> is already in use. <? } ?>Contact the site admin if it should be updated.</p>
	<? } ?>
	<form method="post" action="/admin/artworks/<?= $artwork->ArtworkId ?>">
		<input type="hidden" name="_method" value="PATCH" />
		<button name="status" value="<?= COVER_ARTWORK_STATUS_APPROVED ?>" <? if($existingArtwork !== null){ ?>disabled="disabled"<? } ?>>Approve</button>
		<button name="status" value="<?= COVER_ARTWORK_STATUS_DECLINED ?>" class="decline-button">Decline</button>
	</form>
</main>
<?= Template::Footer() ?>
