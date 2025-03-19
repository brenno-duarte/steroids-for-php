<?php

/**
 * VAR REPRESENTATION
 */

if (!defined('VAR_REPRESENTATION_SINGLE_LINE')) {
    define('VAR_REPRESENTATION_SINGLE_LINE', 1);
}

if (!defined('VAR_REPRESENTATION_UNESCAPED')) {
    define('VAR_REPRESENTATION_UNESCAPED', 2);
}

if (!function_exists('var_representation')) {
    /**
     * Convert a variable to a string in a way that fixes the shortcomings of `var_export()`.
     *
     * @param mixed $value
     * @param int $flags bitmask of flags (VAR_REPRESENTATION_SINGLE_LINE, VAR_REPRESENTATION_UNESCAPED)
     * @suppress PhanRedefineFunctionInternal this is a polyfill
     */
    function var_representation($value, int $flags = 0): string
    {
        return PHPPeclPolyfill\VarRepresentation\Encoder::toVarRepresentation($value, $flags);
    }
}

/**
 * XXTEA -----------------------------------------------------------------------------------
 */

if (!extension_loaded('xxtea')) {
    // public functions
    // $str is the string to be encrypted.
    // $key is the encrypt key. It is the same as the decrypt key.
    function xxtea_encrypt(string $str, string $key)
    {
        return PHPPeclPolyfill\XXTEA\XXTEA::encrypt($str, $key);
    }

    // $str is the string to be decrypted.
    // $key is the decrypt key. It is the same as the encrypt key.
    function xxtea_decrypt(string $str, string $key)
    {
        return PHPPeclPolyfill\XXTEA\XXTEA::decrypt($str, $key);
    }
}

/**
 * YAML -----------------------------------------------------------------------------------
 */

if (!extension_loaded('yaml')) {
    function yaml_parse(string $input): mixed
    {
        if (is_file($input)) {
            throw new PHPPeclPolyfill\YAML\YamlException("File found. Use \"yaml_parse_file\"");
        }

        return PHPPeclPolyfill\YAML\YAML::YAMLLoad($input);
    }

    function yaml_parse_file(string $input): mixed
    {
        if (!is_file($input)) {
            throw new PHPPeclPolyfill\YAML\YamlException("String found. Use \"yaml_parse\"");
        }

        return PHPPeclPolyfill\YAML\YAML::YAMLLoad($input);
    }

    function yaml_emit(array $input): mixed
    {
        return PHPPeclPolyfill\YAML\YAML::YAMLDump($input);
    }
}

/**
 * SIMDJSON -----------------------------------------------------------------------------------
 */

if (!extension_loaded('simdjson')) {
    function simdjson_is_valid(string $json, int $depth = 512)
    {
        return PHPPeclPolyfill\Simdjson\Simdjson::simdjsonIsValid($json, $depth);
    }

    function simdjson_decode(string $json, bool $associative = false, int $depth = 512)
    {
        return PHPPeclPolyfill\Simdjson\Simdjson::simdjsonDecode($json, $associative, $depth);
    }

    function simdjson_key_count(string $json, string $key, int $depth = 512, bool $throw_if_uncountable = false)
    {
        return PHPPeclPolyfill\Simdjson\Simdjson::simdjsonKeyCount($json, $key, $depth, $throw_if_uncountable);
    }

    function simdjson_key_exists(string $json, string $key, int $depth = 512)
    {
        return PHPPeclPolyfill\Simdjson\Simdjson::simdjsonKeyExists($json, $key, $depth);
    }

    function simdjson_key_value(string $json, string $key, bool $associative = false, int $depth = 512)
    {
        return PHPPeclPolyfill\Simdjson\Simdjson::simdjsonKeyValue($json, $key, $associative, $depth);
    }
}

/**
 * STATICTIC -----------------------------------------------------------------------------------
 */

if (!extension_loaded('stats')) {
    function stats_absolute_deviation(array $a): float
    {
        return PHPPeclPolyfill\Statistic\Statistic::stats_absolute_deviation($a);
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
    function stats_cdf_beta(float $par1, float $par2, float $par3, int $which): float
    {
        return PHPPeclPolyfill\Statistic\Statistic::stats_cdf_beta(
            $par1, 
            $par2, 
            $par3, 
            $which
        );
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
    function stats_cdf_binomial(float $par1, float $par2, float $par3, int $which): float
    {
        return PHPPeclPolyfill\Statistic\Statistic::stats_cdf_binomial(
            $par1, 
            $par2, 
            $par3, 
            $which
        );
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
    function stats_cdf_cauchy(float $par1, float $par2, float $par3, int $which): float
    {
        return PHPPeclPolyfill\Statistic\Statistic::stats_cdf_cauchy(
            $par1, 
            $par2, 
            $par3, 
            $which
        );
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
    function stats_cdf_chisquare(float $par1, float $par2, int $which): float
    {
        return PHPPeclPolyfill\Statistic\Statistic::stats_cdf_chisquare($par1, $par2, $which);
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
    function stats_cdf_exponential(float $par1, float $par2, int $which): float
    {
        return PHPPeclPolyfill\Statistic\Statistic::stats_cdf_exponential($par1, $par2, $which);
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
    function stats_cdf_f(float $par1, float $par2, float $par3, int $which): float
    {
        return PHPPeclPolyfill\Statistic\Statistic::stats_cdf_f(
            $par1, 
            $par2, 
            $par3, 
            $which
        );
    }

    function stats_cdf_gamma(float $par1, float $par2, float $par3, int $which): float
    {
        return PHPPeclPolyfill\Statistic\Statistic::stats_cdf_gamma(
            $par1, 
            $par2, 
            $par3, 
            $which
        );
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
    function stats_cdf_laplace(float $par1, float $par2, float $par3, int $which): float
    {
        return PHPPeclPolyfill\Statistic\Statistic::stats_cdf_laplace(
            $par1, 
            $par2, 
            $par3, 
            $which
        );
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
    function stats_cdf_logistic(float $par1, float $par2, float $par3, int $which): float
    {
        return PHPPeclPolyfill\Statistic\Statistic::stats_cdf_logistic(
            $par1, 
            $par2, 
            $par3, 
            $which
        );
    }

    function stats_cdf_negative_binomial(float $par1, float $par2, float $par3, int $which): float
    {
        return PHPPeclPolyfill\Statistic\Statistic::stats_cdf_negative_binomial(
            $par1, 
            $par2, 
            $par3, 
            $which
        );
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
    function stats_cdf_noncentral_chisquare(float $par1, float $par2, float $par3, int $which): float
    {
        return PHPPeclPolyfill\Statistic\Statistic::stats_cdf_noncentral_chisquare(
            $par1, 
            $par2, 
            $par3, 
            $which
        );
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
    function stats_cdf_noncentral_f(float $par1, float $par2, float $par3, float $par4, int $which): float
    {
        return PHPPeclPolyfill\Statistic\Statistic::stats_cdf_noncentral_f(
            $par1, 
            $par2, 
            $par3, 
            $par4, 
            $which
        );
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
    function stats_cdf_noncentral_t(float $par1, float $par2, float $par3, int $which): float
    {
        return PHPPeclPolyfill\Statistic\Statistic::stats_cdf_noncentral_t(
            $par1, 
            $par2, 
            $par3, 
            $which
        );
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
    function stats_cdf_normal(float $par1, float $par2, float $par3, int $which): float
    {
        return PHPPeclPolyfill\Statistic\Statistic::stats_cdf_normal(
            $par1, 
            $par2, 
            $par3, 
            $which
        );
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
    function stats_cdf_poisson(float $par1, float $par2, int $which): float
    {
        return PHPPeclPolyfill\Statistic\Statistic::stats_cdf_poisson($par1, $par2, $which);
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
    function stats_cdf_t(float $par1, float $par2, int $which): float
    {
        return PHPPeclPolyfill\Statistic\Statistic::stats_cdf_t($par1, $par2, $which);
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
    function stats_cdf_uniform(float $par1, float $par2, float $par3, int $which): float
    {
        return PHPPeclPolyfill\Statistic\Statistic::stats_cdf_uniform(
            $par1, 
            $par2, 
            $par3, 
            $which
        );
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
    function stats_cdf_weibull(float $par1, float $par2, float $par3, int $which): float
    {
        return PHPPeclPolyfill\Statistic\Statistic::stats_cdf_weibull(
            $par1, 
            $par2, 
            $par3, 
            $which
        );
    }

    /**
     * Returns the covariance of a and b, or FALSE on failure.
     *
     * @param array $valuesA
     * @param array $valuesB
     *
     * @return float
     */
    function stats_covariance(array $valuesA, array $valuesB): float
    {
        return PHPPeclPolyfill\Statistic\Statistic::stats_covariance($valuesA, $valuesB);
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
    function stats_dens_beta(float $x, float $a, float $b): float
    {
        return PHPPeclPolyfill\Statistic\Statistic::stats_dens_beta($x, $a, $b);
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
    function stats_dens_cauchy(float $x, float $ave, float $stdev): float
    {
        return PHPPeclPolyfill\Statistic\Statistic::stats_dens_cauchy($x, $ave, $stdev);
    }

    /**
     * Probability density function of the chi-square distribution
     *
     * @param float $x The value at which the probability density is calculated
     * @param float $dfr The degree of freedom of the distribution
     *
     * @return float
     */
    function stats_dens_chisquare(float $x, float $dfr): float
    {
        return PHPPeclPolyfill\Statistic\Statistic::stats_dens_chisquare($x, $dfr);
    }

    /**
     * Probability density function of the exponential distribution
     *
     * @param float $x The value at which the probability density is calculated
     * @param float $scale The scale of the distribution
     *
     * @return float
     */
    function stats_dens_exponential(float $x, float $scale): float
    {
        return PHPPeclPolyfill\Statistic\Statistic::stats_dens_exponential($x, $scale);
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
    function stats_dens_f(float $x, float $dfr1, float $dfr2): float
    {
        return PHPPeclPolyfill\Statistic\Statistic::stats_dens_f($x, $dfr1, $dfr2);
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
    function stats_dens_gamma(float $x, float $shape, float $scale): float
    {
        return PHPPeclPolyfill\Statistic\Statistic::stats_dens_gamma($x, $shape, $scale);
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
    function stats_dens_laplace(float $x, float $ave, float $stdev): float
    {
        return PHPPeclPolyfill\Statistic\Statistic::stats_dens_laplace($x, $ave, $stdev);
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
    function stats_dens_logistic(float $x, float $ave, float $stdev): float
    {
        return PHPPeclPolyfill\Statistic\Statistic::stats_dens_logistic($x, $ave, $stdev);
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
    function stats_dens_normal(float $x, float $ave, float $stdev): float
    {
        return PHPPeclPolyfill\Statistic\Statistic::stats_dens_normal($x, $ave, $stdev);
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
    function stats_dens_pmf_binomial(float $x, float $n, float $pi): float
    {
        return PHPPeclPolyfill\Statistic\Statistic::stats_dens_pmf_binomial($x, $n, $pi);
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
    function stats_dens_pmf_hypergeometric(float $n1, float $n2, float $N1, float $N2): float
    {
        return PHPPeclPolyfill\Statistic\Statistic::stats_dens_pmf_hypergeometric($n1, $n2, $N1, $N2);
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
    function stats_dens_pmf_negative_binomial(float $x, float $n, float $pi): float
    {
        return PHPPeclPolyfill\Statistic\Statistic::stats_dens_pmf_negative_binomial($x, $n, $pi);
    }

    /**
     * Probability mass function of the Poisson distribution
     *
     * @param float $x The value at which the probability mass is calculated
     * @param float $lb The parameter of the Poisson distribution
     *
     * @return float
     */
    function stats_dens_pmf_poisson(float $x, float $lb): float
    {
        return PHPPeclPolyfill\Statistic\Statistic::stats_dens_pmf_poisson($x, $lb);
    }

    /**
     * Probability density function of the t-distribution
     *
     * @param float $x The value at which the probability density is calculated
     * @param float $dfr The degree of freedom of the distribution
     *
     * @return float
     */
    function stats_dens_t(float $x, float $dfr): float
    {
        return PHPPeclPolyfill\Statistic\Statistic::stats_dens_t($x, $dfr);
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
    function stats_dens_uniform(float $x, float $a, float $b): float
    {
        return PHPPeclPolyfill\Statistic\Statistic::stats_dens_uniform($x, $a, $b);
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
    function stats_dens_weibull(float $x, float $a, float $b): float
    {
        return PHPPeclPolyfill\Statistic\Statistic::stats_dens_weibull($x, $a, $b);
    }

    /**
     * Returns the harmonic mean of the values in a, or FALSE if a is empty or is not an array
     *
     * @return float|int
     */
    function stats_harmonic_mean(): float|int
    {
        $sum = null;
        $num_args = func_num_args();

        for ($i = 0; $i < $num_args; $i++) {
            $sum += 1 / func_get_arg($i);
        }

        return $num_args / $sum;
    }

    /**
     * Returns the kurtosis of the values in a, or FALSE if a is empty or is not an array.
     *
     * @param array $values
     *
     * @return bool
     */
    function stats_kurtosis(array $values): bool
    {
        return PHPPeclPolyfill\Statistic\Statistic::stats_kurtosis($values);
    }

    /**
     * Generates a random deviate from the beta distribution
     *
     * @param float $a
     * @param float $b
     *
     * @return float
     */
    function stats_rand_gen_beta(float $a, float $b): float
    {
        return PHPPeclPolyfill\Statistic\Statistic::stats_rand_gen_beta($a, $b);
    }

    /**
     * Generates a random deviate from the chi-square distribution
     *
     * @param float $df The degrees of freedom
     * 
     * @return float
     */
    function stats_rand_gen_chisquare(float $df): float
    {
        return PHPPeclPolyfill\Statistic\Statistic::stats_rand_gen_chisquare($df);
    }

    /**
     * Generates a random deviate from the exponential distribution
     *
     * @param float $av The scale parameter
     * 
     * @return float
     */
    function stats_rand_gen_exponential(float $av): float
    {
        return PHPPeclPolyfill\Statistic\Statistic::stats_rand_gen_exponential($av);
    }

    /**
     * Generates a random deviate from the F distribution
     *
     * @param float $dfn The degrees of freedom in the numerator
     * @param float $dfd The degrees of freedom in the denominator
     * 
     * @return float
     */
    function stats_rand_gen_f(float $dfn, float $dfd): float
    {
        return PHPPeclPolyfill\Statistic\Statistic::stats_rand_gen_f($dfn, $dfd);
    }

    /**
     * Generates uniform float between low (exclusive) and high (exclusive)
     *
     * @param float $low The lower bound (inclusive)
     * @param float $high The upper bound (exclusive)
     * 
     * @return float
     */
    function stats_rand_gen_funiform(float $low, float $high): float
    {
        return PHPPeclPolyfill\Statistic\Statistic::stats_rand_gen_funiform($low, $high);
    }

    /**
     * Generates a random deviate from the gamma distribution
     *
     * @param float $a location parameter of Gamma distribution (a > 0)
     * @param float $r shape parameter of Gamma distribution (r > 0)
     * 
     * @return float
     */
    function stats_rand_gen_gamma(float $a, float $r): float
    {
        return PHPPeclPolyfill\Statistic\Statistic::stats_rand_gen_gamma($a, $r);
    }

    /**
     * Generates a random deviate from the binomial distribution
     *
     * @param int $n The number of trials
     * @param float $pp The probability of an event in each trial
     * 
     * @return int
     */
    function stats_rand_gen_ibinomial(int $n, float $pp): int
    {
        return PHPPeclPolyfill\Statistic\Statistic::stats_rand_gen_ibinomial($n, $pp);
    }

    /**
     * Generates a random deviate from the negative binomial distribution
     *
     * @param int $n The number of success
     * @param float $p The success rate
     * 
     * @return int
     */
    function stats_rand_gen_ibinomial_negative(int $n, float $p): int
    {
        return PHPPeclPolyfill\Statistic\Statistic::stats_rand_gen_ibinomial_negative($n, $p);
    }

    /**
     * Generates random integer between 1 and 2147483562
     *
     * @return int
     */
    function stats_rand_gen_int(): int
    {
        return PHPPeclPolyfill\Statistic\Statistic::stats_rand_gen_int();
    }

    /**
     * Generates a single random deviate from a Poisson distribution
     *
     * @param float $mu The parameter of the Poisson distribution
     * 
     * @return int
     */
    function stats_rand_gen_ipoisson(float $mu): int
    {
        return PHPPeclPolyfill\Statistic\Statistic::stats_rand_gen_ipoisson($mu);
    }

    /**
     * Generates integer uniformly distributed between LOW (inclusive) and HIGH (inclusive)
     *
     * @param int $low The lower bound
     * @param int $high The upper bound
     * 
     * @return int
     */
    function stats_rand_gen_iuniform(int $low, int $high): int
    {
        return PHPPeclPolyfill\Statistic\Statistic::stats_rand_gen_iuniform($low, $high);
    }

    /**
     * Generates a random deviate from the non-central chi-square distribution
     *
     * @param float $df The degrees of freedom
     * @param float $xnonc The non-centrality parameter
     * 
     * @return float
     */
    function stats_rand_gen_noncentral_chisquare(float $df, float $xnonc): float
    {
        return PHPPeclPolyfill\Statistic\Statistic::stats_rand_gen_noncentral_chisquare($df, $xnonc);
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
    function stats_rand_gen_noncentral_f(float $dfn, float $dfd, float $xnonc): float
    {
        return PHPPeclPolyfill\Statistic\Statistic::stats_rand_gen_noncentral_f($dfn, $dfd, $xnonc);
    }

    /**
     * Generates a single random deviate from a non-central t-distribution
     *
     * @param float $df The degrees of freedom
     * @param float $xnonc The non-centrality parameter
     * 
     * @return float
     */
    function stats_rand_gen_noncentral_t(float $df, float $xnonc): float
    {
        return PHPPeclPolyfill\Statistic\Statistic::stats_rand_gen_noncentral_t($df, $xnonc);
    }

    /**
     * Generates a single random deviate from a normal distribution
     *
     * @param float $av The mean of the normal distribution
     * @param float $sd The standard deviation of the normal distribution
     * 
     * @return float
     */
    function stats_rand_gen_normal(float $av, float $sd): float
    {
        return PHPPeclPolyfill\Statistic\Statistic::stats_rand_gen_normal($av, $sd); 
    }

    /**
     * Generates a single random deviate from a t-distribution
     *
     * @param float $df The degrees of freedom
     * 
     * @return float
     */
    function stats_rand_gen_t(float $df): float
    {
        return PHPPeclPolyfill\Statistic\Statistic::stats_rand_gen_t($df);
    }

    /**
     * Get the seed values of the random number generator
     *
     * @return array
     */
    function stats_rand_get_seeds(): array
    {
        return PHPPeclPolyfill\Statistic\Statistic::stats_rand_get_seeds();
    }

    /**
     * Generate two seeds for the RGN random number generator
     *
     * @param string $phrase The input phrase
     * 
     * @return array
     */
    function stats_rand_phrase_to_seeds(string $phrase): array
    {
        return PHPPeclPolyfill\Statistic\Statistic::stats_rand_phrase_to_seeds($phrase);
    }

    /**
     * Generates a random floating point number between 0 and 1
     *
     * @return float
     */
    function stats_rand_ranf(): float
    {
        return PHPPeclPolyfill\Statistic\Statistic::stats_rand_ranf();
    }

    /**
     * Set seed values to the random generator
     *
     * @param int $iseed1 The value which is used as the random seed
     * @param int $iseed2 The value which is used as the random seed
     * 
     * @return void
     */
    function stats_rand_setall(int $iseed1, int $iseed2): void
    {
        PHPPeclPolyfill\Statistic\Statistic::stats_rand_setall($iseed1, $iseed2);
    }

    /**
     * Returns the skewness of the values in a, or FALSE if a is empty or is not an array
     *
     * @param array $values
     *
     * @return bool|float
     */
    function stats_skew(array $values): bool|float
    {
        return PHPPeclPolyfill\Statistic\Statistic::stats_skew($values);
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
    function stats_standard_deviation(array $a, bool $sample = false): float|bool
    {
        return PHPPeclPolyfill\Statistic\Statistic::stats_standard_deviation($a, $sample);
    }

    /**
     * Returns a binomial coefficient
     *
     * @param int $x The number of chooses from the set
     * @param int $n The number of elements in the set
     *
     * @return float
     */
    function stats_stat_binomial_coef(int $x, int $n): float
    {
        return PHPPeclPolyfill\Statistic\Statistic::stats_stat_binomial_coef($x, $n);
    }

    /**
     * Returns the Pearson correlation coefficient of two data sets
     *
     * @param array $x
     * @param array $b
     * @return float
     */
    function stats_stat_correlation(array $x, array $y)
    {
        return PHPPeclPolyfill\Statistic\Statistic::stats_stat_correlation($x, $y);
    }

    /**
     * Returns the factorial of an integer
     *
     * @param int $n An integer
     * 
     * @return float
     */
    function stats_stat_factorial(int $n): float
    {
        return PHPPeclPolyfill\Statistic\Statistic::stats_stat_factorial($n);
    }

    /**
     * Returns the t-value from the independent two-sample t-test
     *
     * @param array $arr1 The first set of values
     * @param array $arr2 The second set of values
     * 
     * @return float
     */
    function stats_stat_independent_t(array $arr1, array $arr2): float
    {
        return PHPPeclPolyfill\Statistic\Statistic::stats_stat_independent_t($arr1, $arr2);
    }

    /**
     * Returns the inner product of two vectors
     *
     * @param array $arr1 The first array
     * @param array $arr2 The second array
     * 
     * @return float
     */
    function stats_stat_innerproduct(array $arr1, array $arr2): float
    {
        return PHPPeclPolyfill\Statistic\Statistic::stats_stat_innerproduct($arr1, $arr2);
    }

    /**
     * Returns the t-value of the dependent t-test for paired samples
     *
     * @param array $arr1 The first samples
     * @param array $arr2 The second samples
     * 
     * @return float
     */
    function stats_stat_paired_t(array $arr1, array $arr2): float
    {
        return PHPPeclPolyfill\Statistic\Statistic::stats_stat_paired_t($arr1, $arr2);
    }

    /**
     * Returns the percentile value
     *
     * @param array $arr
     * @param float $perc
     *
     * @return float
     */
    function stats_stat_percentile(array $arr, float $perc): float
    {
        return PHPPeclPolyfill\Statistic\Statistic::stats_stat_percentile($arr, $perc);
    }

    /**
     * Returns the power sum of a vector
     *
     * @param array $arr The input array
     * @param float $power The power
     * 
     * @return float
     */
    function stats_stat_powersum(array $arr, float $power): float
    {
        return PHPPeclPolyfill\Statistic\Statistic::stats_stat_powersum($arr, $power);
    }

    /**
     * Returns the variance of the values in $data_set
     *
     * @param array $data_set
     *
     * @return float
     */
    function stats_variance(array $data_set)
    {
        return PHPPeclPolyfill\Statistic\Statistic::stats_variance($data_set);
    }
}
