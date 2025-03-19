<?php

namespace PHPPeclPolyfill\Statistic;

use PHPPeclPolyfill\Statistic\AbstractStatisticHelper;
use InvalidArgumentException;

class Statistic extends AbstractStatisticHelper
{
    public static function stats_absolute_deviation(array $a): float
    {
        if (empty($a)) {
            trigger_error("Dataset cannot be empty", E_USER_WARNING);
        }
    
        $meanValue = self::mean($a);
        $sumAbsoluteDifferences = 0;
    
        foreach ($a as $value) {
            $sumAbsoluteDifferences += abs($value - $meanValue);
        }
    
        return $sumAbsoluteDifferences / count($a);
    }

    /**
     * Calculates any one parameter of the beta distribution given values for the others
     *
     * @param float $par1
     * @param float $par2
     * @param float $par3
     * @param int $which The flag to determine what to be calculated
     * 
     * @return float Returns CDF, x, alpha, or beta, determined by which
     */
    public static function stats_cdf_beta(float $par1, float $par2, float $par3, int $which): float
    {
        if ($which == 1) {
            // Calcula a CDF (par1) dado x, alpha e beta
            return self::beta_cdf($par1, $par2, $par3);
        } elseif ($which == 2) {
            // Calcula x dado a CDF, alpha e beta (inverso da CDF, pode envolver métodos numéricos)
            return self::inverse_beta_cdf($par1, $par2, $par3);
        } elseif ($which == 3) {
            // Calcula alpha dado x, CDF e beta
            return self::find_alpha($par1, $par2, $par3);
        } elseif ($which == 4) {
            // Calcula beta dado x, CDF e alpha
            return self::find_beta($par1, $par2, $par3);
        } else {
            trigger_error('$which parameter must be between 1 and 4', E_USER_WARNING);
        }

        return 0;
    }

    /**
     * Calculates any one parameter of the binomial distribution given values for the others
     *
     * @param float $par1
     * @param float $par2
     * @param float $par3
     * @param int $which The flag to determine what to be calculated
     * 
     * @return float Returns CDF, x, n, or p, determined by which
     */
    public static function stats_cdf_binomial(float $par1, float $par2, float $par3, int $which): float
    {
        if ($which == 1) {
            // Calcula a CDF para a distribuição binomial
            return self::binomial_cdf($par1, $par2, $par3);
        } elseif ($which == 2) {
            // Calcula x dado o CDF, n e p
            return self::find_binomial_x($par1, $par2, $par3);
        } elseif ($which == 3) {
            // Calcula n dado x, CDF e p
            return self::find_binomial_n($par1, $par2, $par3);
        } elseif ($which == 4) {
            // Calcula p dado x, CDF e n
            return self::find_binomial_p($par1, $par2, $par3);
        } else {
            trigger_error('$which parameter must be between 1 and 4', E_USER_WARNING);
        }

        return 0;
    }

    /**
     * Calculates any one parameter of the Cauchy distribution given values for the others
     *
     * @param float $par1
     * @param float $par2
     * @param float $par3
     * @param int $which The flag to determine what to be calculated
     * 
     * @return float Returns CDF, x, x0, or gamma, determined by which
     */
    public static function stats_cdf_cauchy(float $par1, float $par2, float $par3, int $which): float
    {
        if ($which == 1) {
            // Calcula a CDF dado x, x0 e gamma
            return self::cauchy_cdf($par1, $par2, $par3);
        } elseif ($which == 2) {
            // Calcula x dado a CDF, x0 e gamma
            return self::inverse_cauchy_cdf($par1, $par2, $par3);
        } elseif ($which == 3) {
            // Calcula x0 dado x, CDF e gamma
            return self::find_cauchy_x0($par1, $par2, $par3);
        } elseif ($which == 4) {
            // Calcula gamma dado x, CDF e x0
            return self::find_cauchy_gamma($par1, $par2, $par3);
        } else {
            trigger_error('$which parameter must be between 1 and 4', E_USER_WARNING);
        }

        return 0;
    }

    /**
     * Calculates any one parameter of the chi-square distribution given values for the others
     *
     * @param float $par1
     * @param float $par2
     * @param int $which
     * 
     * @return float
     */
    public static function stats_cdf_chisquare(float $par1, float $par2, int $which): float
    {
        if ($which == 1) {
            // Calcula a CDF dado x (par1) e k (par2)
            return self::chisquare_cdf($par1, $par2);
        } elseif ($which == 2) {
            // Calcula x dado CDF (par1) e k (par2)
            return self::find_chisquare_x($par1, $par2);
        } elseif ($which == 3) {
            // Calcula k dado x (par1) e CDF (par2)
            return self::find_chisquare_k($par1, $par2);
        } else {
            trigger_error('$which parameter must be between 1 and 4', E_USER_WARNING);
        }

        return 0;
    }

    /**
     * Calculates any one parameter of the exponential distribution given values for the others
     *
     * @param float $par1
     * @param float $par2
     * @param int $which
     * 
     * @return float
     */
    public static function stats_cdf_exponential(float $par1, float $par2, int $which): float
    {
        if ($which == 1) {
            // Calcula a CDF dado x (par1) e lambda (par2)
            return self::exponential_cdf($par1, $par2);
        } elseif ($which == 2) {
            // Calcula x dado CDF (par1) e lambda (par2)
            return self::find_exponential_x($par1, $par2);
        } elseif ($which == 3) {
            // Calcula lambda dado x (par1) e CDF (par2)
            return self::find_exponential_lambda($par1, $par2);
        } else {
            trigger_error('$which parameter must be between 1 and 4', E_USER_WARNING);
        }

        return 0;
    }

    /**
     * Calculates any one parameter of the F distribution given values for the others
     *
     * @param float $par1
     * @param float $par2
     * @param float $par3
     * @param int $which
     * 
     * @return float
     */
    public static function stats_cdf_f(float $par1, float $par2, float $par3, int $which): float
    {
        if ($which == 1) {
            // Calcula a CDF para a distribuição F
            return self::f_cdf($par1, $par2, $par3);
        } elseif ($which == 2) {
            // Calcula x dado o CDF e graus de liberdade (d1 e d2)
            return self::find_f_x($par1, $par2, $par3);
        } elseif ($which == 3) {
            // Calcula d1 dado x, CDF e d2
            return self::find_f_d1($par1, $par2, $par3);
        } elseif ($which == 4) {
            // Calcula d2 dado x, CDF e d1
            return self::find_f_d2($par1, $par2, $par3);
        } else {
            trigger_error('$which parameter must be between 1 and 4', E_USER_WARNING);
        }

        return 0;
    }

    public static function stats_cdf_gamma(float $par1, float $par2, float $par3, int $which): float
    {
        if ($which == 1) {
            // Calcula a CDF dado x, k e theta
            return self::gamma_cdf($par1, $par2, $par3);
        } elseif ($which == 2) {
            // Calcula x dado a CDF, k e theta
            return self::find_gamma_x($par1, $par2, $par3);
        } elseif ($which == 3) {
            // Calcula k dado x, CDF e theta
            return self::find_gamma_k($par1, $par2, $par3);
        } elseif ($which == 4) {
            // Calcula theta dado x, CDF e k
            return self::find_gamma_theta($par1, $par2, $par3);
        } else {
            trigger_error('$which parameter must be between 1 and 4', E_USER_WARNING);
        }

        return 0;
    }

    /**
     * Calculates any one parameter of the Laplace distribution given values for the others
     *
     * @param float $par1
     * @param float $par2
     * @param float $par3
     * @param int $which
     * 
     * @return float
     */
    public static function stats_cdf_laplace(float $par1, float $par2, float $par3, int $which): float
    {
        if ($which == 1) {
            // Calcula a CDF dado x, mu e b
            return self::laplace_cdf($par1, $par2, $par3);
        } elseif ($which == 2) {
            // Calcula x dado a CDF, mu e b
            return self::find_laplace_x($par1, $par2, $par3);
        } elseif ($which == 3) {
            // Calcula mu dado x, CDF e b
            return self::find_laplace_mu($par1, $par2, $par3);
        } elseif ($which == 4) {
            // Calcula b dado x, CDF e mu
            return self::find_laplace_b($par1, $par2, $par3);
        } else {
            trigger_error('$which parameter must be between 1 and 4', E_USER_WARNING);
        }

        return 0;
    }

    /**
     * Calculates any one parameter of the logistic distribution given values for the others
     *
     * @param float $par1
     * @param float $par2
     * @param float $par3
     * @param int $which
     * 
     * @return float
     */
    public static function stats_cdf_logistic(float $par1, float $par2, float $par3, int $which): float
    {
        if ($which == 1) {
            // Calcula a CDF dado x, mu e s
            return self::logistic_cdf($par1, $par2, $par3);
        } elseif ($which == 2) {
            // Calcula x dado a CDF, mu e s
            return self::find_logistic_x($par1, $par2, $par3);
        } elseif ($which == 3) {
            // Calcula mu dado x, CDF e s
            return self::find_logistic_mu($par1, $par2, $par3);
        } elseif ($which == 4) {
            // Calcula s dado x, CDF e mu
            return self::find_logistic_s($par1, $par2, $par3);
        } else {
            trigger_error('$which parameter must be between 1 and 4', E_USER_WARNING);
        }

        return 0;
    }

    public static function stats_cdf_negative_binomial(float $par1, float $par2, float $par3, int $which): float
    {
        if ($which == 1) {
            // Calcula a CDF dado x, r e p
            return self::negative_binomial_cdf($par1, $par2, $par3);
        } elseif ($which == 2) {
            // Calcula x dado CDF, r e p
            return self::find_negative_binomial_x($par1, $par2, $par3);
        } elseif ($which == 3) {
            // Calcula r dado x, CDF e p
            return self::find_negative_binomial_r($par1, $par2, $par3);
        } elseif ($which == 4) {
            // Calcula p dado x, CDF e r
            return self::find_negative_binomial_p($par1, $par2, $par3);
        } else {
            trigger_error('$which parameter must be between 1 and 4', E_USER_WARNING);
        }

        return 0;
    }

    /**
     * Calculates any one parameter of the non-central chi-square distribution given values for the others
     *
     * @param float $par1
     * @param float $par2
     * @param float $par3
     * @param int $which
     * 
     * @return float
     */
    public static function stats_cdf_noncentral_chisquare(float $par1, float $par2, float $par3, int $which): float
    {
        if ($which == 1) {
            // Calcula a CDF dado x, k e lambda
            return self::noncentral_chisquare_cdf($par1, $par2, $par3);
        } elseif ($which == 2) {
            // Calcula x dado CDF, k e lambda
            return self::find_noncentral_chisquare_x($par1, $par2, $par3);
        } elseif ($which == 3) {
            // Calcula k dado x, CDF e lambda
            return self::find_noncentral_chisquare_k($par1, $par2, $par3);
        } elseif ($which == 4) {
            // Calcula lambda dado x, CDF e k
            return self::find_noncentral_chisquare_lambda($par1, $par2, $par3);
        } else {
            trigger_error('$which parameter must be between 1 and 4', E_USER_WARNING);
        }

        return 0;
    }

    /**
     * Calculates any one parameter of the non-central F distribution given values for the others
     *
     * @param float $par1
     * @param float $par2
     * @param float $par3
     * @param float $par4
     * @param int $which
     * 
     * @return float
     */
    public static function stats_cdf_noncentral_f(float $par1, float $par2, float $par3, float $par4, int $which): float
    {
        if ($which == 1) {
            // Calcula a CDF dado x, nu1, nu2 e lambda
            return self::noncentral_f_cdf($par1, $par2, $par3, $par4);
        } elseif ($which == 2) {
            // Calcula x dado a CDF, nu1, nu2 e lambda
            return self::find_noncentral_f_x($par1, $par2, $par3, $par4);
        } elseif ($which == 3) {
            // Calcula nu1 dado x, CDF, nu2 e lambda
            return self::find_noncentral_f_nu1($par1, $par2, $par3, $par4);
        } elseif ($which == 4) {
            // Calcula nu2 dado x, CDF, nu1 e lambda
            return self::find_noncentral_f_nu2($par1, $par2, $par3, $par4);
        } elseif ($which == 5) {
            // Calcula lambda dado x, CDF, nu1 e nu2
            return self::find_noncentral_f_lambda($par1, $par2, $par3, $par4);
        } else {
            trigger_error('$which parameter must be between 1 and 4', E_USER_WARNING);
        }

        return 0;
    }

    /**
     * Calculates any one parameter of the non-central t-distribution give values for the others
     *
     * @param float $par1
     * @param float $par2
     * @param float $par3
     * @param int $which
     * 
     * @return float
     */
    public static function stats_cdf_noncentral_t(float $par1, float $par2, float $par3, int $which): float
    {
        if ($which == 1) {
            // Calcula a CDF dado x, nu e mu
            return self::noncentral_t_cdf($par1, $par2, $par3);
        } elseif ($which == 2) {
            // Calcula x dado CDF, nu e mu
            return self::find_noncentral_t_x($par1, $par2, $par3);
        } elseif ($which == 3) {
            // Calcula nu dado x, CDF e mu
            return self::find_noncentral_t_nu($par1, $par2, $par3);
        } elseif ($which == 4) {
            // Calcula mu dado x, CDF e nu
            return self::find_noncentral_t_mu($par1, $par2, $par3);
        } else {
            trigger_error('$which parameter must be between 1 and 4', E_USER_WARNING);
        }

        return 0;
    }

    /**
     * Calculates any one parameter of the normal distribution given values for the others
     *
     * @param float $par1
     * @param float $par2
     * @param float $par3
     * @param int $which
     * 
     * @return float
     */
    public static function stats_cdf_normal(float $par1, float $par2, float $par3, int $which): float
    {
        if ($which == 1) {
            // Calcula a CDF dado x, mu e sigma
            return self::normal_cdf($par1, $par2, $par3);
        } elseif ($which == 2) {
            // Calcula x dado a CDF, mu e sigma
            return self::find_normal_x($par1, $par2, $par3);
        } elseif ($which == 3) {
            // Calcula mu dado x, CDF e sigma
            return self::find_normal_mu($par1, $par2, $par3);
        } elseif ($which == 4) {
            // Calcula sigma dado x, CDF e mu
            return self::find_normal_sigma($par1, $par2, $par3);
        } else {
            trigger_error('$which parameter must be between 1 and 4', E_USER_WARNING);
        }

        return 0;
    }

    /**
     * Calculates any one parameter of the Poisson distribution given values for the others
     *
     * @param float $par1
     * @param float $par2
     * @param int $which
     * 
     * @return float
     */
    public static function stats_cdf_poisson(float $par1, float $par2, int $which): float
    {
        if ($which == 1) {
            // Calcula a CDF dado x e lambda
            return self::poisson_cdf($par1, $par2);
        } elseif ($which == 2) {
            // Calcula x dado a CDF e lambda
            return self::find_poisson_x($par1, $par2);
        } elseif ($which == 3) {
            // Calcula lambda dado x e CDF
            return self::find_poisson_lambda($par1, $par2);
        } else {
            trigger_error('$which parameter must be between 1 and 4', E_USER_WARNING);
        }

        return 0;
    }

    /**
     * Calculates any one parameter of the t-distribution given values for the others
     *
     * @param float $par1
     * @param float $par2
     * @param int $which
     * 
     * @return float
     */
    public static function stats_cdf_t(float $par1, float $par2, int $which): float
    {
        if ($which == 1) {
            // Calcula a CDF dado x e graus de liberdade (nu)
            return self::t_cdf($par1, $par2);
        } elseif ($which == 2) {
            // Calcula x dado a CDF e graus de liberdade (nu)
            return self::find_t_x($par1, $par2);
        } elseif ($which == 3) {
            // Calcula graus de liberdade (nu) dado x e CDF
            return self::find_t_nu($par1, $par2);
        } else {
            trigger_error('$which parameter must be between 1 and 4', E_USER_WARNING);
        }

        return 0;
    }

    /**
     * Calculates any one parameter of the uniform distribution given values for the others
     *
     * @param float $par1
     * @param float $par2
     * @param float $par3
     * @param int $which
     * 
     * @return float
     */
    public static function stats_cdf_uniform(float $par1, float $par2, float $par3, int $which): float
    {
        if ($which == 1) {
            // Calcula a CDF dado x, a e b
            return self::uniform_cdf($par1, $par2, $par3);
        } elseif ($which == 2) {
            // Calcula x dado a CDF, a e b
            return self::find_uniform_x($par1, $par2, $par3);
        } elseif ($which == 3) {
            // Calcula a dado x, CDF e b
            return self::find_uniform_a($par1, $par2, $par3);
        } elseif ($which == 4) {
            // Calcula b dado x, CDF e a
            return self::find_uniform_b($par1, $par2, $par3);
        } else {
            trigger_error('$which parameter must be between 1 and 4', E_USER_WARNING);
        }

        return 0;
    }

    /**
     * Calculates any one parameter of the Weibull distribution given values for the others
     *
     * @param float $par1
     * @param float $par2
     * @param float $par3
     * @param int $which
     * 
     * @return float
     */
    public static function stats_cdf_weibull(float $par1, float $par2, float $par3, int $which): float
    {
        if ($which == 1) {
            // Calcula a CDF dado x, k e lambda
            return self::weibull_cdf($par1, $par2, $par3);
        } elseif ($which == 2) {
            // Calcula x dado a CDF, k e lambda
            return self::find_weibull_x($par1, $par2, $par3);
        } elseif ($which == 3) {
            // Calcula k dado x, CDF e lambda
            return self::find_weibull_k($par1, $par2, $par3);
        } elseif ($which == 4) {
            // Calcula lambda dado x, CDF e k
            return self::find_weibull_lambda($par1, $par2, $par3);
        } else {
            trigger_error('$which parameter must be between 1 and 4', E_USER_WARNING);
        }

        return 0;
    }

    /**
     * Returns the covariance of a and b, or FALSE on failure.
     *
     * @param array $valuesA
     * @param array $valuesB
     *
     * @return float
     */
    public static function stats_covariance(array $valuesA, array $valuesB): float
    {
        $countA = count($valuesA);
        $countB = count($valuesB);

        if ($countA != $countB) {
            trigger_error('Arrays with different sizes: countA=' . $countA . ', countB=' . $countB, E_USER_WARNING);
        }

        if ($countA < 0) {
            trigger_error('Empty arrays', E_USER_WARNING);
        }

        $meanA = array_sum($valuesA) / floatval($countA);
        $meanB = array_sum($valuesB) / floatval($countB);
        $add = 0.0;

        for ($pos = 0; $pos < $countA; $pos++) {
            $valueA = $valuesA[$pos];
            if (!is_numeric($valueA)) {
                trigger_error('Not numerical value in array A at position ' . $pos . ', value=' . $valueA, E_USER_WARNING);
            }

            $valueB = $valuesB[$pos];
            if (!is_numeric($valueB)) {
                trigger_error('Not numerical value in array B at position ' . $pos . ', value=' . $valueB, E_USER_WARNING);
            }

            $difA = $valueA - $meanA;
            $difB = $valueB - $meanB;
            $add += ($difA * $difB);
        }  // for

        return $add / floatval($countA);
    }

    /**
     * Probability density function of the beta distribution
     *
     * @param float $x The value at which the probability density is calculated
     * @param float $a The shape parameter of the distribution
     * @param float $b The shape parameter of the distribution
     *
     * @return float
     */
    public static function stats_dens_beta(float $x, float $a, float $b): float
    {
        if ($x <= 0 || $x >= 1) {
            return 0;  // A densidade fora do intervalo (0, 1) é zero
        }

        $numerator = pow($x, $a - 1) * pow(1 - $x, $b - 1);
        $denominator = self::beta($a, $b);
        return $numerator / $denominator;
    }

    /**
     * Probability density function of the Cauchy distribution
     *
     * @param float $x The value at which the probability density is calculated
     * @param float $ave The location parameter of the distribution
     * @param float $stdev The scale parameter of the distribution
     *
     * @return float
     */
    public static function stats_dens_cauchy(float $x, float $ave, float $stdev): float
    {
        if ($stdev <= 0) {
            trigger_error('Param $stdev must be greater than 0', E_USER_WARNING);
        }

        $numerator = 1;
        $denominator = M_PI * $stdev * (1 + pow(($x - $ave) / $stdev, 2));

        return $numerator / $denominator;
    }

    /**
     * Probability density function of the chi-square distribution
     *
     * @param float $x The value at which the probability density is calculated
     * @param float $dfr The degree of freedom of the distribution
     *
     * @return float
     */
    public static function stats_dens_chisquare(float $x, float $dfr): float
    {
        if ($x < 0) {
            return 0;  // Para valores negativos, a densidade é 0
        }

        $k = $dfr;
        $numerator = pow($x, ($k / 2) - 1) * exp(-$x / 2);
        $denominator = pow(2, $k / 2) * self::gamma($k / 2);

        return $numerator / $denominator;
    }

    /**
     * Probability density function of the exponential distribution
     *
     * @param float $x The value at which the probability density is calculated
     * @param float $scale The scale of the distribution
     *
     * @return float
     */
    public static function stats_dens_exponential(float $x, float $scale): float
    {
        if ($scale <= 0) {
            trigger_error('Param $scale (λ) must be greater than 0', E_USER_WARNING);
        }

        if ($x < 0) {
            return 0;  // Para valores negativos de x, a densidade é 0
        }

        return $scale * exp(-$scale * $x);
    }

    /**
     * Probability density function of the F distribution
     *
     * @param float $x The value at which the probability density is calculated
     * @param float $dfr1 The degree of freedom of the distribution
     * @param float $dfr2 The degree of freedom of the distribution
     *
     * @return float
     */
    public static function stats_dens_f(float $x, float $dfr1, float $dfr2): float
    {
        if ($x <= 0) {
            return 0;  // A densidade para x <= 0 é zero
        }

        $numerator = sqrt(pow(
            $dfr1 * $x,
            $dfr1
        ) * pow(
            $dfr2,
            $dfr2
        ) / pow(
            $dfr1 * $x + $dfr2,
            $dfr1 + $dfr2
        ));

        $denominator = $x * self::beta($dfr1 / 2, $dfr2 / 2);
        return $numerator / $denominator;
    }

    /**
     * Probability density function of the gamma distribution
     *
     * @param float $x The value at which the probability density is calculated
     * @param float $shape The shape parameter of the distribution
     * @param float $scale The scale parameter of the distribution
     *
     * @return float
     */
    public static function stats_dens_gamma(float $x, float $shape, float $scale): float
    {
        if ($x < 0) {
            return 0;  // Para valores negativos de x, a densidade é 0
        }

        $numerator = pow($x, $shape - 1) * exp(-$x / $scale);
        $denominator = pow($scale, $shape) * self::gamma($shape);

        return $numerator / $denominator;
    }

    /**
     * Probability density function of the Laplace distribution
     *
     * @param float $x The value at which the probability density is calculated
     * @param float $ave The location parameter of the distribution
     * @param float $stdev The shape parameter of the distribution
     *
     * @return float
     */
    public static function stats_dens_laplace(float $x, float $ave, float $stdev): float
    {
        if ($stdev <= 0) {
            trigger_error('Param $stdev (b) must be greater than 0', E_USER_WARNING);
        }

        $numerator = exp(-abs($x - $ave) / $stdev);
        $denominator = 2 * $stdev;

        return $numerator / $denominator;
    }

    /**
     * Probability density function of the logistic distribution
     *
     * @param float $x The value at which the probability density is calculated
     * @param float $ave The location parameter of the distribution
     * @param float $stdev The shape parameter of the distribution
     *
     * @return float
     */
    public static function stats_dens_logistic(float $x, float $ave, float $stdev): float
    {
        if ($stdev <= 0) {
            trigger_error('Param $stdev (b) must be greater than 0', E_USER_WARNING);
        }

        $expTerm = exp(-($x - $ave) / $stdev);
        $denominator = $stdev * pow(1 + $expTerm, 2);

        return $expTerm / $denominator;
    }

    /**
     * Probability density function of the normal distribution
     *
     * @param float $x The value at which the probability density is calculated
     * @param float $ave The mean of the distribution
     * @param float $stdev The standard deviation of the distribution
     *
     * @return float
     */
    public static function stats_dens_normal(float $x, float $ave, float $stdev): float
    {
        if ($stdev <= 0) {
            trigger_error('Param $stdev (b) must be greater than 0', E_USER_WARNING);
        }

        $coefficient = 1 / ($stdev * sqrt(2 * M_PI));
        $exponent = exp(-pow($x - $ave, 2) / (2 * pow($stdev, 2)));

        return $coefficient * $exponent;
    }

    /**
     * Probability mass function of the binomial distribution
     *
     * @param float $x The value at which the probability mass is calculated
     * @param float $n The number of trials of the distribution
     * @param float $pi The success rate of the distribution
     *
     * @return float
     */
    public static function stats_dens_pmf_binomial(float $x, float $n, float $pi): float
    {
        if ($pi < 0 || $pi > 1) {
            trigger_error('The probability (pi) must be between 0 and 1', E_USER_WARNING);
        }

        if ($x < 0 || $x > $n) {
            return 0;  // Fora do intervalo válido, a probabilidade é zero
        }

        $binomialCoeff = self::binomialCoefficient($n, $x);
        return $binomialCoeff * pow($pi, $x) * pow(1 - $pi, $n - $x);
    }

    /**
     * Probability mass function of the hypergeometric distribution
     *
     * @param float $n1 The number of success, at which the probability mass is calculated
     * @param float $n2 The number of failure of the distribution
     * @param float $N1 The number of success samples of the distribution
     * @param float $N2 The number of failure samples of the distribution
     *
     * @return float
     */
    public static function stats_dens_pmf_hypergeometric(float $n1, float $n2, float $N1, float $N2): float
    {
        if ($n1 < 0 || $n1 > $N1 || $n1 > $N2 || $N2 > $n2) {
            return 0;  // Valores inválidos resultam em probabilidade zero
        }

        $numerator = self::binomialCoefficient($N1, $n1) * self::binomialCoefficient($n2 - $N1, $N2 - $n1);
        $denominator = self::binomialCoefficient($n2, $N2);

        return $numerator / $denominator;
    }

    /**
     * Probability mass function of the negative binomial distribution
     *
     * @param float $x The value at which the probability mass is calculated
     * @param float $n The number of the success of the distribution
     * @param float $pi The success rate of the distribution
     *
     * @return float
     */
    public static function stats_dens_pmf_negative_binomial(float $x, float $n, float $pi): float
    {
        if ($pi < 0 || $pi > 1) {
            trigger_error('The probability (pi) must be between 0 and 1', E_USER_WARNING);
        }

        if ($x < 0) {
            return 0;  // Probabilidade é zero para valores negativos de k
        }

        $binomialCoeff = self::binomialCoefficient($x + $n - 1, $x);
        return $binomialCoeff * pow($pi, $n) * pow(1 - $pi, $x);
    }

    /**
     * Probability mass function of the Poisson distribution
     *
     * @param float $x The value at which the probability mass is calculated
     * @param float $lb The parameter of the Poisson distribution
     *
     * @return float
     */
    public static function stats_dens_pmf_poisson(float $x, float $lb): float
    {
        if ($lb <= 0) {
            trigger_error('The lambda param (λ) must be greater than 0', E_USER_WARNING);
        }

        if ($x < 0) {
            return 0;  // A probabilidade é zero para valores negativos de x
        }

        return (exp(-$lb) * pow($lb, $x)) / stats_stat_factorial($x);
    }

    /**
     * Probability density function of the t-distribution
     *
     * @param float $x The value at which the probability density is calculated
     * @param float $dfr The degree of freedom of the distribution
     *
     * @return float
     */
    public static function stats_dens_t(float $x, float $dfr): float
    {
        if ($dfr <= 0) {
            trigger_error('The degree of freedom (dfr) must be greater than 0', E_USER_WARNING);
        }

        $numerator = self::gamma(($dfr + 1) / 2);
        $denominator = sqrt($dfr * M_PI) * self::gamma($dfr / 2);
        $coefficient = $numerator / $denominator;

        $term = pow(1 + ($x * $x) / $dfr, -($dfr + 1) / 2);

        return $coefficient * $term;
    }

    /**
     * Probability density function of the uniform distribution
     *
     * @param float $x The value at which the probability density is calculated
     * @param float $a The lower bound of the distribution
     * @param float $b The upper bound of the distribution
     *
     * @return float
     */
    public static function stats_dens_uniform(float $x, float $a, float $b): float
    {
        if ($a >= $b) {
            trigger_error('The lower limit (a) must be less than the upper limit (b)', E_USER_WARNING);
        }

        if ($x < $a || $x > $b) {
            return 0;  // Fora do intervalo [a, b], a densidade é 0
        }

        return 1 / ($b - $a);  // Densidade uniforme dentro do intervalo
    }

    /**
     * Probability density function of the Weibull distribution
     *
     * @param float $x The value at which the probability density is calculated
     * @param float $a The shape parameter of the distribution
     * @param float $b The scale parameter of the distribution
     *
     * @return float
     */
    public static function stats_dens_weibull(float $x, float $a, float $b): float
    {
        if ($a <= 0 || $b <= 0) {
            trigger_error('The shape (a) and scale (b) parameters must be greater than 0', E_USER_WARNING);
        }

        if ($x < 0) {
            return 0;  // A densidade é 0 para valores negativos de x
        }

        $term1 = ($a / $b);
        $term2 = pow($x / $b, $a - 1);
        $term3 = exp(-pow($x / $b, $a));

        return $term1 * $term2 * $term3;
    }

    /**
     * @deprecated Don't use this method
     */
    public static function stats_harmonic_mean(): never
    {
        exit("Don't use this method");
    }

    /**
     * Returns the kurtosis of the values in a, or FALSE if a is empty or is not an array.
     *
     * @param array $values
     *
     * @return bool
     */
    public static function stats_kurtosis(array $values): bool
    {
        $numValues = count($values);
        if ($numValues == 0) {
            return 0.0;
        }

        $mean = array_sum($values) / floatval($numValues);
        $add2 = 0.0;
        $add4 = 0.0;

        foreach ($values as $value) {
            if (!is_numeric($value)) {
                return false;
            }
            $dif = $value - $mean;
            $dif2 = $dif * $dif;
            $add2 += $dif2;
            $add4 += ($dif2 * $dif2);
        }  // foreach values

        $variance = $add2 / floatval($numValues);
        return ($add4 * $numValues) / ($add2 * $add2) - 3.0;
    }

    /**
     * Generates a random deviate from the beta distribution
     *
     * @param float $a
     * @param float $b
     *
     * @return float
     */
    public static function stats_rand_gen_beta(float $a, float $b): float
    {
        if ($a <= 0 || $b <= 0) {
            trigger_error("Parameters a and b must be greater than 0", E_USER_WARNING);
        }
    
        $y1 = self::gammaRandom($a);
        $y2 = self::gammaRandom($b);
    
        return $y1 / ($y1 + $y2); // Transformação para Beta
    }

    /**
     * Generates a random deviate from the chi-square distribution
     *
     * @param float $df The degrees of freedom
     * 
     * @return float
     */
    public static function stats_rand_gen_chisquare(float $df): float
    {
        if ($df <= 0) {
            trigger_error("Degrees of freedom (df) must be greater than 0", E_USER_WARNING);
        }
    
        $sum = 0;

        for ($i = 0; $i < $df; $i++) {
            $z = self::randNormal(); // Variável normal padrão
            $sum += $z * $z; // Soma dos quadrados
        }
    
        return $sum;
    }

    /**
     * Generates a random deviate from the exponential distribution
     *
     * @param float $av The scale parameter
     * 
     * @return float
     */
    public static function stats_rand_gen_exponential(float $av): float {
        if ($av <= 0) {
            throw new InvalidArgumentException("Param \$av must be greater than 0");
        }
    
        $u = mt_rand() / mt_getrandmax(); // Número aleatório U(0, 1)
        return -log($u) / $av; // Transformação para a distribuição exponencial
    }

    /**
     * Generates a random deviate from the F distribution
     *
     * @param float $dfn The degrees of freedom in the numerator
     * @param float $dfd The degrees of freedom in the denominator
     * 
     * @return float
     */
    public static function stats_rand_gen_f(float $dfn, float $dfd): float
    {
        if ($dfn <= 0 || $dfd <= 0) {
            trigger_error("The degrees of freedom of the numerator and denominator must be greater than 0", E_USER_WARNING);
        }
    
        $chiSquareNumerator = stats_rand_gen_chisquare($dfn); // Qui-quadrado para o numerador
        $chiSquareDenominator = stats_rand_gen_chisquare($dfd); // Qui-quadrado para o denominador
    
        return ($chiSquareNumerator / $dfn) / ($chiSquareDenominator / $dfd); // Razão F
    }

    /**
     * Generates uniform float between low (exclusive) and high (exclusive)
     *
     * @param float $low The lower bound (inclusive)
     * @param float $high The upper bound (exclusive)
     * 
     * @return float
     */
    public static function stats_rand_gen_funiform(float $low, float $high): float
    {
        if ($low >= $high) {
            trigger_error("The lower limit (low) must be less than the upper limit (high)", E_USER_WARNING);
        }
    
        $random = mt_rand() / mt_getrandmax(); // Gera um número aleatório entre 0 e 1
        return $low + $random * ($high - $low); // Escala para o intervalo
    }

    /**
     * Generates a random deviate from the gamma distribution
     *
     * @param float $a location parameter of Gamma distribution (a > 0)
     * @param float $r shape parameter of Gamma distribution (r > 0)
     * 
     * @return float
     */
    public static function stats_rand_gen_gamma(float $a, float $r): float
    {
        if ($a <= 0 || $r <= 0) {
            trigger_error("The parameters \$a (scale) and \$r (shape) must be greater than 0", E_USER_WARNING);
        }
    
        if ($r < 1) {
            // Transformação para r < 1
            $c = 1 + $r * exp(-1);
            while (true) {
                $u = mt_rand() / mt_getrandmax();
                $v = mt_rand() / mt_getrandmax();
    
                $x = $c * $u;
                if ($x <= 1) {
                    $z = pow($x, 1 / $r);
                    if ($v <= exp(-$z)) {
                        return $z / $a;
                    }
                } else {
                    $z = -log(($c - $x) / $r);
                    if ($v <= pow($z, $r - 1)) {
                        return $z / $a;
                    }
                }
            }
        } else {
            // Método de Marsaglia-Tsang para r >= 1
            $d = $r - 1 / 3;
            $c = 1 / sqrt(9 * $d);
    
            while (true) {
                do {
                    $x = mt_rand() / mt_getrandmax() * 2 - 1;
                    $v = 1 + $c * $x;
                } while ($v <= 0);
    
                $v = pow($v, 3);
                $u = mt_rand() / mt_getrandmax();
    
                if ($u < 1 - 0.0331 * pow($x, 4)) {
                    return $d * $v / $a;
                }
    
                if ($u < exp(0.5 * pow($x, 2) + $d * (1 - $v + log($v)))) {
                    return $d * $v / $a;
                }
            }
        }
    }

    /**
     * Generates a random deviate from the binomial distribution
     *
     * @param int $n The number of trials
     * @param float $pp The probability of an event in each trial
     * 
     * @return int
     */
    public static function stats_rand_gen_ibinomial(int $n, float $pp): int
    {
        if ($n <= 0 || $pp < 0 || $pp > 1) {
            trigger_error("The number of trials (n) must be greater than 0 and the probability (p) must be between 0 and 1", E_USER_WARNING);
        }
    
        $successes = 0;
    
        for ($i = 0; $i < $n; $i++) {
            $random = mt_rand() / mt_getrandmax(); // Gera um número aleatório entre 0 e 1

            if ($random < $pp) {
                $successes++; // Incrementa o número de sucessos se o valor aleatório for menor que p
            }
        }
    
        return $successes;
    }

    /**
     * Generates a random deviate from the negative binomial distribution
     *
     * @param int $n The number of success
     * @param float $p The success rate
     * 
     * @return int
     */
    public static function stats_rand_gen_ibinomial_negative(int $n, float $p): int
    {
        if ($n <= 0 || $p <= 0 || $p > 1) {
            throw new InvalidArgumentException("O número de sucessos (n) deve ser maior que 0 e a probabilidade (p) deve estar no intervalo (0, 1].");
        }
    
        $failures = 0;
    
        for ($i = 0; $i < $n; $i++) {
            $failures += self::randGeometric($p) - 1; // Soma as falhas antes de cada sucesso
        }
    
        return $failures;
    }

    /**
     * Generates random integer between 1 and 2147483562
     *
     * @return int
     */
    public static function stats_rand_gen_int(): int
    {
        return mt_rand(1, 2147483562);
    }

    /**
     * Generates a single random deviate from a Poisson distribution
     *
     * @param float $mu The parameter of the Poisson distribution
     * 
     * @return int
     */
    public static function stats_rand_gen_ipoisson(float $mu): int
    {
        if ($mu <= 0) {
            trigger_error("Param \$mu must be greater than 0", E_USER_WARNING);
        }
    
        $L = exp(-$mu); // Limite
        $p = 1.0;
        $k = 0;
    
        do {
            $k++;
            $p *= mt_rand() / mt_getrandmax(); // Gera um número aleatório entre 0 e 1
        } while ($p > $L);
    
        return $k - 1;
    }

    /**
     * Generates integer uniformly distributed between LOW (inclusive) and HIGH (inclusive)
     *
     * @param int $low The lower bound
     * @param int $high The upper bound
     * 
     * @return int
     */
    public static function stats_rand_gen_iuniform(int $low, int $high): int
    {
        if ($low > $high) {
            trigger_error("The lower limit (low) must be less than or equal to the upper limit (high)", E_USER_WARNING);
        }
    
        return mt_rand($low, $high); // Gera um número inteiro aleatório entre low e high (inclusivo)
    }

    /**
     * Generates a random deviate from the non-central chi-square distribution
     *
     * @param float $df The degrees of freedom
     * @param float $xnonc The non-centrality parameter
     * 
     * @return float
     */
    public static function stats_rand_gen_noncentral_chisquare(float $df, float $xnonc): float
    {
        if ($df <= 0 || $xnonc < 0) {
            trigger_error("The degrees of freedom (df) must be greater than 0 and the non-centrality parameter (xnonc) must be greater than or equal to 0", E_USER_WARNING);
        }
    
        // Variável normal não central (com média sqrt(lambda) e variância 1)
        $nonCentralComponent = pow(self::randNormal() + sqrt($xnonc), 2);
    
        // Variável qui-quadrado com df-1 graus de liberdade
        $chiSquareComponent = stats_rand_gen_chisquare($df - 1);
    
        return $nonCentralComponent + $chiSquareComponent;
    }
    
    /**
     * Generates a random deviate from the noncentral F distribution
     *
     * @param float $dfn The degrees of freedom of the numerator
     * @param float $dfd The degrees of freedom of the denominator
     * @param float $xnonc The non-centrality parameter
     * 
     * @return float
     */
    public static function stats_rand_gen_noncentral_f(float $dfn, float $dfd, float $xnonc): float
    {
        if ($dfn <= 0 || $dfd <= 0 || $xnonc < 0) {
            trigger_error("The degrees of freedom (dfn, dfd) must be greater than 0 and the non-centrality parameter (xnonc) must be greater than or equal to 0", E_USER_WARNING);
        }
    
        // Gera valores aleatórios das distribuições qui-quadrado não centrais
        $numerator = self::stats_rand_gen_noncentral_chisquare($dfn, $xnonc); // Numerador
        $denominator = stats_rand_gen_chisquare($dfd); // Denominador
    
        // Calcula o valor F não central
        return ($numerator / $dfn) / ($denominator / $dfd);
    }

    /**
     * Generates a single random deviate from a non-central t-distribution
     *
     * @param float $df The degrees of freedom
     * @param float $xnonc The non-centrality parameter
     * 
     * @return float
     */
    public static function stats_rand_gen_noncentral_t(float $df, float $xnonc): float
    {
        if ($df <= 0 || $xnonc < 0) {
            trigger_error("The degrees of freedom (df) must be greater than 0 and the non-centrality parameter (xnonc) must be greater than or equal to 0", E_USER_WARNING);
        }
    
        // Componente normal, ajustado pelo parâmetro de não centralidade
        $numerator = self::randNormal() + $xnonc;
    
        // Componente qui-quadrado ajustado pelos graus de liberdade
        $denominator = sqrt(stats_rand_gen_chisquare($df) / $df);
    
        // Valor t não central
        return $numerator / $denominator;
    }

    /**
     * Generates a single random deviate from a normal distribution
     *
     * @param float $av The mean of the normal distribution
     * @param float $sd The standard deviation of the normal distribution
     * 
     * @return float
     */
    public static function stats_rand_gen_normal(float $av, float $sd): float
    {
        if ($sd <= 0) {
            throw new InvalidArgumentException("OThe standard deviation (\$sd) must be greater than 0");
        }

        $z0 = self::randNormal();
        return $z0 * $sd + $av;    
    }

    /**
     * Generates a single random deviate from a t-distribution
     *
     * @param float $df The degrees of freedom
     * 
     * @return float
     */
    public static function stats_rand_gen_t(float $df): float
    {
        if ($df <= 0) {
            trigger_error("Degrees of freedom (df) must be greater than 0", E_USER_WARNING);
        }
    
        // Numerador: variável normal padrão
        $numerator = self::randNormal();
    
        // Denominador: raiz quadrada da variável qui-quadrado dividida por df
        $denominator = sqrt(stats_rand_gen_chisquare($df) / $df);
    
        return $numerator / $denominator; // Valor da distribuição t
    }

    /**
     * Get the seed values of the random number generator
     *
     * @return array
     */
    public static function stats_rand_get_seeds(): array
    {
        // Simulamos as sementes geradas por mt_rand com base no tempo e em um número randômico
        $seed1 = mt_rand(1, 2147483647); // Gera uma semente aleatória dentro do intervalo
        $seed2 = mt_rand(1, 2147483647); // Gera uma segunda semente aleatória
    
        return [$seed1, $seed2];
    }

    /**
     * Generate two seeds for the RGN random number generator
     *
     * @param string $phrase The input phrase
     * 
     * @return array
     */
    public static function stats_rand_phrase_to_seeds(string $phrase): array
    {
        if (empty($phrase)) {
            throw new InvalidArgumentException("The phrase cannot be empty");
        }
    
        // Gera um hash MD5 da frase
        $hash = md5($phrase);
    
        // Divide o hash em duas partes e converte cada uma para um número inteiro
        $seed1 = hexdec(substr($hash, 0, 16)); // Primeiros 16 caracteres do hash
        $seed2 = hexdec(substr($hash, 16, 16)); // Últimos 16 caracteres do hash
    
        // Retorna as sementes como um array
        return [$seed1, $seed2];
    }

    public static function stats_rand_ranf(): float
    {
        // Gera um número aleatório no intervalo aberto (0, 1)
        // Garantimos que o valor não seja 0 substituindo números muito próximos de 0
        do {
            $random = mt_rand() / mt_getrandmax(); // Número aleatório entre 0 e 1
        } while ($random == 0); // Rejeita valores iguais a 0
    
        return $random;
    }

    /**
     * Set seed values to the random generator
     *
     * @param int $iseed1 The value which is used as the random seed
     * @param int $iseed2 The value which is used as the random seed
     * 
     * @return void
     */
    public static function stats_rand_setall(int $iseed1, int $iseed2): void
    {
        if ($iseed1 < 1 || $iseed1 > 2147483562 || $iseed2 < 1 || $iseed2 > 2147483398) {
            trigger_error("The values ​​of iseed1 and iseed2 must be in the ranges: 1 ≤ iseed1 ≤ 2147483562 and 1 ≤ iseed2 ≤ 2147483398", E_USER_WARNING);
        }
    
        // Combina as sementes de forma única para inicializar o gerador de números aleatórios
        $combinedSeed = ($iseed1 + $iseed2) % mt_getrandmax();
        mt_srand($combinedSeed);
    }

    /**
     * Returns the skewness of the values in a, or FALSE if a is empty or is not an array
     *
     * @param array $values
     *
     * @return bool|float
     */
    public static function stats_skew(array $values): bool|float
    {
        $numValues = count($values);

        if ($numValues == 0) {
            return 0.0;
        }

        $mean = array_sum($values) / floatval($numValues);

        $add2 = 0.0;
        $add3 = 0.0;
        foreach ($values as $value) {
            if (!is_numeric($value)) {
                return false;
            }

            $dif = $value - $mean;
            $add2 += ($dif * $dif);
            $add3 += ($dif * $dif * $dif);
        }  // foreach values

        $variance = $add2 / floatval($numValues);

        return ($add3 / floatval($numValues)) / pow($variance, 3 / 2.0);
    }

    /**
     * This user-land implementation follows the implementation quite strictly;
     * it does not attempt to improve the code or algorithm in any way. It will
     * raise a warning if you have fewer than 2 values in your array, just like
     * the extension does (although as an E_USER_WARNING, not E_WARNING).
     *
     * @param array $a
     * @param bool $sample [optional] Defaults to false
     * @return float|bool The standard deviation or false on error.
     */
    public static function stats_standard_deviation(array $a, bool $sample = false): float|bool
    {
        $n = count($a);
        if ($n === 0) {
            trigger_error('The array has zero elements', E_USER_WARNING);
            return false;
        }
        if ($sample && $n === 1) {
            trigger_error('The array has only 1 element', E_USER_WARNING);
            return false;
        }
        $mean = array_sum($a) / $n;
        $carry = 0.0;
        foreach ($a as $val) {
            $d = ((float) $val) - $mean;
            $carry += $d * $d;
        };
        if ($sample) {
            --$n;
        }
        return sqrt($carry / $n);
    }

    /**
     * Returns a binomial coefficient
     *
     * @param int $x The number of chooses from the set
     * @param int $n The number of elements in the set
     *
     * @return float
     */
    public static function stats_stat_binomial_coef(int $x, int $n): float
    {
        if ($x > $n || $x < 0 || $n < 0) {
            trigger_error("The values \u{200B}\u{200B}of x and n must satisfy: 0 <= x <= n", E_USER_WARNING);
        }

        return stats_stat_factorial($n) / (stats_stat_factorial($x) * stats_stat_factorial($n - $x));
    }

    /**
     * Returns the Pearson correlation coefficient of two data sets
     *
     * @param array $x
     * @param array $b
     * @return float
     */
    public static function stats_stat_correlation(array $x, array $y)
    {
        $length = count($x);
        $mean1 = array_sum($x) / $length;
        $mean2 = array_sum($y) / $length;

        $a = 0;
        $b = 0;
        $axb = 0;
        $a2 = 0;
        $b2 = 0;

        for ($i = 0; $i < $length; $i++) {
            $a = $x[$i] - $mean1;
            $b = $y[$i] - $mean2;
            $axb = $axb + ($a * $b);
            $a2 = $a2 + pow($a, 2);
            $b2 = $b2 + pow($b, 2);
        }

        $corr = $axb / sqrt($a2 * $b2);
        return $corr;
    }

    /**
     * Returns the factorial of an integer
     *
     * @param int $n An integer
     * 
     * @return float
     */
    public static function stats_stat_factorial(int $n): float
    {
        if ($n == 0 || $n == 1) {
            return 1;
        }

        return $n * stats_stat_factorial($n - 1);
    }

    /**
     * Returns the t-value from the independent two-sample t-test
     *
     * @param array $arr1 The first set of values
     * @param array $arr2 The second set of values
     * 
     * @return float
     */
    public static function stats_stat_independent_t(array $arr1, array $arr2): float
    {
        $n1 = count($arr1);
        $n2 = count($arr2);
    
        if ($n1 <= 1 || $n2 <= 1) {
            trigger_error("Each sample must have at least two elements", E_USER_WARNING);
        }
    
        $mean1 = self::mean($arr1);
        $mean2 = self::mean($arr2);
    
        $var1 = self::variance($arr1);
        $var2 = self::variance($arr2);
    
        // Calcula o denominador
        $denominator = sqrt(($var1 / $n1) + ($var2 / $n2));
    
        if ($denominator == 0) {
            trigger_error("The denominator is zero. Check the variances or sample sizes", E_USER_WARNING);
        }
    
        // Calcula o valor t
        $t = ($mean1 - $mean2) / $denominator;    
        return $t;
    }

    /**
     * Returns the inner product of two vectors
     *
     * @param array $arr1 The first array
     * @param array $arr2 The second array
     * 
     * @return float
     */
    public static function stats_stat_innerproduct(array $arr1, array $arr2): float
    {
        if (count($arr1) !== count($arr2)) {
            trigger_error("Arrays must be the same size", E_USER_WARNING);
        }
    
        $result = 0;

        for ($i = 0; $i < count($arr1); $i++) {
            $result += $arr1[$i] * $arr2[$i];
        }
    
        return $result;
    }

    /**
     * Returns the t-value of the dependent t-test for paired samples
     *
     * @param array $arr1 The first samples
     * @param array $arr2 The second samples
     * 
     * @return float
     */
    public static function stats_stat_paired_t(array $arr1, array $arr2): float
    {
        if (count($arr1) !== count($arr2)) {
            throw new InvalidArgumentException("Os dois conjuntos de dados devem ter o mesmo tamanho.");
        }
    
        $n = count($arr1);
        if ($n <= 1) {
            throw new InvalidArgumentException("O número de pares deve ser maior que 1.");
        }
    
        // Calculando as diferenças
        $differences = [];
        for ($i = 0; $i < $n; $i++) {
            $differences[] = $arr1[$i] - $arr2[$i];
        }
    
        // Calculando a média e o desvio padrão das diferenças
        $meanDifference = self::mean($differences);
        $stdDevDifference = self::standardDeviation($differences);
    
        // Calculando o valor t
        $t = $meanDifference / ($stdDevDifference / sqrt($n));
    
        return $t;
    }

    /**
     * Returns the percentile value
     *
     * @param array $arr
     * @param float $perc
     *
     * @return float
     */
    public static function stats_stat_percentile(array $arr, float $perc): float
    {
        $count = count($arr);
        sort($arr, SORT_NUMERIC);
        $low = floor(0.01 * $perc * $count);
        // $max = floor(0.01 * (100 - $perc) * $count);
        $percvar = $arr[$low];

        return $percvar;
    }

    /**
     * Returns the power sum of a vector
     *
     * @param array $arr The input array
     * @param float $power The power
     * 
     * @return float
     */
    public static function stats_stat_powersum(array $arr, float $power): float
    {
        $sum = 0.0;
    
        foreach ($arr as $value) {
            $sum += pow($value, $power);
        }
    
        return $sum;
    }

    /**
     * Returns the variance of the values in $data_set
     *
     * @param array $data_set
     *
     * @return float
     */
    public static function stats_variance(array $data_set)
    {
        $mean = array_sum($data_set) / count($data_set);

        $squared_sum = 0.0;
        foreach ($data_set as $data_point) {
            $deviation_from_mean = $data_point - $mean;
            $squared_sum += pow($deviation_from_mean, 2);
        }

        return $squared_sum / count($data_set);
    }
}
