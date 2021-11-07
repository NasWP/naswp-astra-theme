<?php
/**
 * Třída s proměnnými, které lze využít v helperech i jinde
 * Počítá se s přidáváním dalších proměnných uživatelem
 *
 * @author Vladimír Smitka, Lynt.cz
 *
 */
if (!class_exists('NasWP_Settings')) {
	class NasWP_Settings
	{
		public $mimes = array(
			'svg' => 'image/svg+xml'
		);
	}
}
