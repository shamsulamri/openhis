
			if ($encounter->type_code=='sponsored') {
					if (!empty($tier->tier_sponsor_multiplier)) {
							$value = $tier->tier_sponsor_multiplier*$price;
							if (!empty($tier->tier_sponsor_limit)) {
								if ($value>$tier->tier_sponsor_limit) $value = $tier->tier_sponsor_limit;
							}
					} elseif (!empty($tier->tier_sponsor)) {
							$value = $tier->tier_sponsor;
					} else {
							$value = $price;
					}

			}

			if ($encounter->type_code=='public') {
					if (!empty($tier->tier_public_multiplier)) {
							$value = $tier->tier_public_multiplier*$price;
							if (!empty($tier->tier_public_limit)) {
								if ($value>$tier->tier_public_limit) $value = $tier->tier_public_limit;
							}
					} elseif (!empty($tier->tier_public)) {
							$value = $tier->tier_public;
					} else {
							$value = $price;
					}

			}
