<?php
/**
 * Archive des films : liste de tous les contenus de type "film".
 *
 * Hiérarchie des templates : l'URL /film/ → archive-film.php
 * (avant archive.php puis index.php).
 */

get_header();
?>

<header class="page-header alignwide">
	<h1 class="page-title">Tous les films</h1>
</header>

<?php if ( have_posts() ) : ?>

	<ul class="film-liste">

		<?php while ( have_posts() ) : the_post(); ?>

			<?php $annee = get_post_meta( get_the_ID(), 'annee', true ); ?>

			<li class="film-liste__item">
				<a class="film-liste__lien" href="<?php echo esc_url( get_permalink() ); ?>">
					<?php echo esc_html( get_the_title() ); ?>
				</a>

				<?php if ( $annee ) : ?>
					<span class="film-liste__annee">(<?php echo absint( $annee ); ?>)</span>
				<?php endif; ?>
			</li>

		<?php endwhile; ?>

	</ul>

	<?php the_posts_pagination(); ?>

<?php else : ?>

	<p class="film-liste__vide">Aucun film pour le moment.</p>

<?php endif; ?>

<?php
get_footer();
