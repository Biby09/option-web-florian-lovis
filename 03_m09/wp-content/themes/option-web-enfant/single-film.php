<?php
/**
 * Template d'un film (type de contenu "film").
 *
 * WordPress choisit ce fichier automatiquement grâce à la hiérarchie des templates :
 * un contenu de type "film" → single-film.php.
 */

get_header();
?>

<?php while ( have_posts() ) : the_post(); ?>

	<?php $annee = get_post_meta( get_the_ID(), 'annee', true ); ?>

	<article id="post-<?php the_ID(); ?>" <?php post_class( 'film-fiche' ); ?>>

		<header class="entry-header">
			<?php the_title( '<h1 class="entry-title film-fiche__titre">', '</h1>' ); ?>
		</header>

		<?php if ( $annee ) : ?>
			<p class="film-fiche__annee">
				Année de sortie : <?php echo absint( $annee ); ?>
			</p>
		<?php endif; ?>

		<div class="entry-content film-fiche__contenu">
			<?php the_content(); ?>
		</div>

	</article>

<?php endwhile; ?>

<?php
get_footer();
