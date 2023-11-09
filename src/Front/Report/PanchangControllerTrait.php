<?php

namespace Prokerala\WP\Astrology\Front\Report;

trait PanchangControllerTrait
{

	/**
	 * Muhurat timing
	 *
	 * @param array<string,mixed> $muhurat muhuratdetails.
	 * @return array
	 */
	public function getAdvancedInfo(array $muhurat ): array
	{
		$muhurat_details = [];
		foreach ( $muhurat as $data ) {
			$field   = $data->getName();
			$periods = $data->getPeriod();
			foreach ( $periods as $period ) {
				$muhurat_details[ $field ][] = [
					'start' => $period->getStart(),
					'end'   => $period->getEnd(),
				];
			}
		}

		return $muhurat_details;
	}

	/**
	 * Panchang Details
	 *
	 * @param array<string,mixed> $panchang panchang data.
	 * @return array
	 */
	public function getPanchangDetails( array $panchang ): array
	{ // phpcs:ignore Generic.Metrics.CyclomaticComplexity.TooHigh
		$data_list       = [ 'Nakshatra', 'Tithi', 'Karana', 'Yoga' ];
		$panchang_result = [];

		foreach ( $data_list as $key ) {
			foreach ( $panchang[ $key ] as $idx => $data ) {
				$panchang_result[ $key ][ $idx ] = [
					'id'    => $data->getId(),
					'name'  => $data->getName(),
					'start' => $data->getStart(),
					'end'   => $data->getEnd(),
				];
				if ( 'Nakshatra' === $key ) {
					$panchang_result[ $key ][ $idx ]['nakshatra_lord'] = $data->getLord();
				} elseif ( 'Tithi' === $key ) {
					$panchang_result[ $key ][ $idx ]['paksha'] = $data->getPaksha();
				}
			}
		}

		return $panchang_result;
	}

}
