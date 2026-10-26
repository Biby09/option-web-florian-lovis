<?php
/**
 * Plugin Name: OW Notes
 * Description: Affiche une note enregistrée hors du thème, modifiable depuis l'administration.
 * Version:     1.0.0
 * Author:      Florian Lovis
 * Text Domain: ow-notes
 */

// Protection : si le fichier est ouvert directement (hors de WordPress), on arrête tout.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* =========================================================================
 * 1. Activation : créer la note par défaut
 * ========================================================================= */

register_activation_hook( __FILE__, 'ow_note_activation' );

/**
 * Exécutée UNE SEULE FOIS, quand on clique sur « Activer » dans Extensions.
 * add_option() ne remplace pas une valeur existante : une note déjà modifiée est conservée.
 */
function ow_note_activation(): void {
	add_option( 'ow_note', 'Première note.' );
}

/* =========================================================================
 * 2. Shortcode [ow_note] : affichage public
 * ========================================================================= */

add_shortcode( 'ow_note', 'ow_note_shortcode' );

/**
 * Remplace [ow_note] dans une page par la note enregistrée.
 * Un shortcode doit RENVOYER le HTML (return), jamais l'afficher (echo).
 */
function ow_note_shortcode(): string {
	$note = get_option( 'ow_note', 'Aucune note.' );

	// SORTIE : un texte venu de la base passe par esc_html().
	return '<p class="ow-note">' . esc_html( $note ) . '</p>';
}

/* =========================================================================
 * 3. Page d'administration : Réglages → OW Notes
 * ========================================================================= */

add_action( 'admin_menu', 'ow_note_menu' );

/**
 * Ajoute la page dans le menu Réglages.
 */
function ow_note_menu(): void {
	add_options_page(
		'OW Notes',          // titre de l'onglet du navigateur
		'OW Notes',          // texte dans le menu
		'manage_options',    // droit requis : seulement les administrateurs
		'ow-notes',          // slug de la page (dans l'URL)
		'ow_note_page'       // fonction qui affiche la page
	);
}

/**
 * Affiche et traite le formulaire.
 */
function ow_note_page(): void {
	// Double sécurité : un utilisateur sans le droit ne voit rien.
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$enregistre = false;

	// Le formulaire a été envoyé ?
	if ( isset( $_POST['ow_note'] ) ) {
		// NONCE : vérifie que l'envoi vient bien de NOTRE formulaire (sinon WordPress arrête tout).
		check_admin_referer( 'ow_note_enregistrer' );

		// ENTRÉE : wp_unslash() retire les \ ajoutés par WordPress, sanitize_text_field() nettoie.
		$note = sanitize_text_field( wp_unslash( $_POST['ow_note'] ) );

		update_option( 'ow_note', $note );
		$enregistre = true;
	}

	$note = get_option( 'ow_note', '' );
	?>
	<div class="wrap">
		<h1>OW Notes</h1>

		<?php if ( $enregistre ) : ?>
			<div class="notice notice-success is-dismissible">
				<p>Note enregistrée.</p>
			</div>
		<?php endif; ?>

		<form method="post">
			<?php wp_nonce_field( 'ow_note_enregistrer' ); ?>

			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="ow_note">Note</label></th>
					<td>
						<input
							type="text"
							id="ow_note"
							name="ow_note"
							class="regular-text"
							value="<?php echo esc_attr( $note ); ?>"
						>
						<p class="description">Affichée partout où le shortcode <code>[ow_note]</code> est utilisé.</p>
					</td>
				</tr>
			</table>

			<?php submit_button( 'Enregistrer' ); ?>
		</form>
	</div>
	<?php
}
