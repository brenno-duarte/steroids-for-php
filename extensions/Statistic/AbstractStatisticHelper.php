<?php

namespace PHPPeclPolyfill\Statistic;

use InvalidArgumentException;
use RuntimeException;

abstract class AbstractStatisticHelper
{
    protected static function gamma($z)
    {
        // Aproximação de Stirling para a função gamma
        if ($z < 0.5) {
            return M_PI / (sin(M_PI * $z) * self::gamma(1 - $z));
        } else {
            $z -= 1;
            $x = 0.99999999999980993;
            $p = [
                676.5203681218851, -1259.1392167224028,
                771.32342877765313, -176.61502916214059,
                12.507343278686905, -0.13857109526572012,
                9.9843695780195716e-6, 1.5056327351493116e-7
            ];
            for ($i = 0; $i < count($p); $i++) {
                $x += $p[$i] / ($z + $i + 1);
            }
            $t = $z + count($p) - 0.5;
            return sqrt(2 * M_PI) * pow($t, $z + 0.5) * exp(-$t) * $x;
        }
    }

    protected static function gammaRandom($shape)
    {
        if ($shape <= 0) {
            trigger_error('The shape parameter must be greater than 0', E_USER_WARNING);
        }

        // Método de Marsaglia-Tsang para gerar valores Gamma
        $d = $shape - 1.0 / 3.0;
        $c = 1.0 / sqrt(9.0 * $d);

        while (true) {
            $x = mt_rand() / mt_getrandmax() * 2.0 - 1.0;  // Número aleatório U(-1, 1)
            $v = pow(1.0 + $c * $x, 3);

            if ($v > 0 && mt_rand() / mt_getrandmax() < exp(-0.5 * pow($x, 2) - $d * (1.0 - $v + log($v)))) {
                return $d * $v;
            }
        }
    }

    protected static function beta($a, $b)
    {
        // Calcula a função beta usando a função gama
        return self::gamma($a) * self::gamma($b) / self::gamma($a + $b);
    }

    protected static function binomialCoefficient($n, $x)
    {
        // Calcula o coeficiente binomial (n choose x)
        return stats_stat_factorial($n) / (stats_stat_factorial($x) * stats_stat_factorial($n - $x));
    }

    protected static function mean($arr)
    {
        return array_sum($arr) / count($arr);
    }

    protected static function variance($arr)
    {
        $mean = self::mean($arr);
        $sum = 0;
        foreach ($arr as $value) {
            $sum += pow($value - $mean, 2);
        }
        return $sum / (count($arr) - 1);  // Variância amostral (dividido por n-1)
    }

    protected static function standardDeviation($arr)
    {
        $mean = self::mean($arr);
        $sum = 0;
        foreach ($arr as $value) {
            $sum += pow($value - $mean, 2);
        }
        return sqrt($sum / (count($arr) - 1));  // Desvio padrão amostral
    }

    protected static function randNormal()
    {
        // Gera um número aleatório a partir de uma distribuição normal padrão (média 0, desvio padrão 1)
        $u1 = mt_rand() / mt_getrandmax();
        $u2 = mt_rand() / mt_getrandmax();

        $z = sqrt(-2 * log($u1)) * cos(2 * M_PI * $u2);  // Método Box-Muller
        return $z;
    }

    protected static function randGeometric($p)
    {
        if ($p <= 0 || $p > 1) {
            trigger_error('The probability (p) must be in the interval (0, 1]', E_USER_WARNING);
        }

        $u = mt_rand() / mt_getrandmax();  // Número aleatório U(0, 1)
        return (int) ceil(log(1 - $u) / log(1 - $p));  // Transformação para distribuição geométrica
    }

    protected static function beta_cdf($x, $alpha, $beta)
    {
        if ($x < 0 || $x > 1) {
            throw new InvalidArgumentException('x deve estar no intervalo [0, 1].');
        }

        $betaFunc = self::beta_function($alpha, $beta);
        $result = 0;

        // Integração numérica usando somas discretas
        $steps = 1000;  // Aumente este valor para maior precisão
        $dx = $x / $steps;

        for ($i = 0; $i <= $steps; $i++) {
            $t = $i * $dx;
            $result += pow($t, $alpha - 1) * pow(1 - $t, $beta - 1) / $betaFunc * $dx;
        }

        return $result;
    }

    protected static function beta_function($alpha, $beta)
    {
        return self::gamma($alpha) * self::gamma($beta) / self::gamma($alpha + $beta);
    }

    protected static function inverse_beta_cdf($cdf, $alpha, $beta)
    {
        if ($cdf < 0 || $cdf > 1) {
            throw new InvalidArgumentException('O CDF deve estar no intervalo [0, 1].');
        }

        $low = 0;
        $high = 1;
        $tolerance = 1.0e-6;  // Precisão do resultado

        while ($high - $low > $tolerance) {
            $mid = ($low + $high) / 2;
            $calculatedCDF = self::beta_cdf($mid, $alpha, $beta);

            if ($calculatedCDF < $cdf) {
                $low = $mid;
            } else {
                $high = $mid;
            }
        }

        return ($low + $high) / 2;  // Aproximação de x
    }

    protected static function find_alpha($x, $cdf, $beta)
    {
        $alpha = 1;  // Valor inicial
        $tolerance = 1.0e-6;
        $maxIterations = 1000;

        for ($i = 0; $i < $maxIterations; $i++) {
            $calculatedCDF = self::beta_cdf($x, $alpha, $beta);

            if (abs($calculatedCDF - $cdf) < $tolerance) {
                return $alpha;  // Encontrei alpha
            }

            // Ajustar alpha com base na diferença
            if ($calculatedCDF < $cdf) {
                $alpha += 0.1;
            } else {
                $alpha -= 0.1;
            }
        }

        throw new RuntimeException('Não foi possível encontrar alpha dentro do número máximo de iterações.');
    }

    protected static function find_beta($x, $cdf, $alpha)
    {
        $beta = 1;  // Valor inicial
        $tolerance = 1.0e-6;
        $maxIterations = 1000;

        for ($i = 0; $i < $maxIterations; $i++) {
            $calculatedCDF = self::beta_cdf($x, $alpha, $beta);

            if (abs($calculatedCDF - $cdf) < $tolerance) {
                return $beta;  // Encontrei beta
            }

            // Ajustar beta com base na diferença
            if ($calculatedCDF < $cdf) {
                $beta += 0.1;
            } else {
                $beta -= 0.1;
            }
        }

        throw new RuntimeException('Não foi possível encontrar beta dentro do número máximo de iterações.');
    }

    protected static function binomial_cdf($x, $n, $p)
    {
        $cdf = 0;
        for ($k = 0; $k <= $x; $k++) {
            $cdf += self::binomial_pmf($k, $n, $p);
        }
        return $cdf;
    }

    protected static function binomial_coefficient($n, $k)
    {
        if ($k > $n) {
            return 0;
        }
        $result = 1;
        for ($i = 1; $i <= $k; $i++) {
            $result *= ($n - $i + 1) / $i;
        }
        return $result;
    }

    protected static function binomial_pmf($x, $n, $p)
    {
        return self::binomial_coefficient($n, $x) * pow($p, $x) * pow(1 - $p, $n - $x);
    }

    protected static function find_binomial_x($cdf, $n, $p)
    {
        $x = 0;
        while ($x <= $n) {
            if (self::binomial_cdf($x, $n, $p) >= $cdf) {
                return $x;
            }
            $x++;
        }
        throw new RuntimeException('Não foi possível encontrar x.');
    }

    // Função para encontrar n dado x, CDF e p
    protected static function find_binomial_n($x, $cdf, $p)
    {
        $n = 1;
        while ($n <= 1000) {  // Limite arbitrário para evitar loop infinito
            if (self::binomial_cdf($x, $n, $p) >= $cdf) {
                return $n;
            }
            $n++;
        }
        throw new RuntimeException('Não foi possível encontrar n.');
    }

    // Função para encontrar p dado x, CDF e n
    protected static function find_binomial_p($x, $cdf, $n)
    {
        $low = 0;
        $high = 1;
        $tolerance = 1.0e-6;

        while ($high - $low > $tolerance) {
            $mid = ($low + $high) / 2;
            if (self::binomial_cdf($x, $n, $mid) < $cdf) {
                $low = $mid;
            } else {
                $high = $mid;
            }
        }
        return ($low + $high) / 2;
    }

    // Função para calcular a CDF da distribuição de Cauchy
    protected static function cauchy_cdf($x, $x0, $gamma)
    {
        return 0.5 + atan(($x - $x0) / $gamma) / M_PI;
    }

    // Função para calcular o valor de x dado a CDF, x0 e gamma (inverso da CDF)
    protected static function inverse_cauchy_cdf($cdf, $x0, $gamma)
    {
        if ($cdf <= 0 || $cdf >= 1) {
            throw new InvalidArgumentException('O CDF deve estar no intervalo (0, 1).');
        }
        return $x0 + $gamma * tan(M_PI * ($cdf - 0.5));
    }

    // Função para encontrar x0 dado x, CDF e gamma
    protected static function find_cauchy_x0($x, $cdf, $gamma)
    {
        if ($cdf <= 0 || $cdf >= 1) {
            throw new InvalidArgumentException('O CDF deve estar no intervalo (0, 1).');
        }
        return $x - $gamma * tan(M_PI * ($cdf - 0.5));
    }

    // Função para encontrar gamma dado x, CDF e x0
    protected static function find_cauchy_gamma($x, $cdf, $x0)
    {
        if ($cdf <= 0 || $cdf >= 1) {
            throw new InvalidArgumentException('O CDF deve estar no intervalo (0, 1).');
        }
        return ($x - $x0) / tan(M_PI * ($cdf - 0.5));
    }

    protected static function chisquare_cdf($x, $k)
    {
        if ($x < 0 || $k <= 0) {
            throw new InvalidArgumentException('x deve ser >= 0 e k > 0.');
        }

        // Integração numérica para aproximação da CDF
        $gammaK = self::gamma($k / 2);
        $result = 0;
        $steps = 1000;
        $dx = $x / $steps;

        for ($i = 0; $i <= $steps; $i++) {
            $t = $i * $dx;
            $result += pow($t, ($k / 2) - 1) * exp(-$t / 2) / ($gammaK * pow(2, $k / 2)) * $dx;
        }

        return $result;
    }

    // Função para encontrar x dado o CDF e k (busca numérica)
    protected static function find_chisquare_x($cdf, $k)
    {
        if ($cdf < 0 || $cdf > 1 || $k <= 0) {
            throw new InvalidArgumentException('CDF deve estar no intervalo [0, 1] e k > 0.');
        }

        $low = 0;
        $high = 100;  // Define um limite superior arbitrário
        $tolerance = 1.0e-6;

        while ($high - $low > $tolerance) {
            $mid = ($low + $high) / 2;
            $calculatedCDF = self::chisquare_cdf($mid, $k);

            if ($calculatedCDF < $cdf) {
                $low = $mid;
            } else {
                $high = $mid;
            }
        }

        return ($low + $high) / 2;
    }

    // Função para encontrar k dado x e CDF (busca iterativa)
    protected static function find_chisquare_k($x, $cdf)
    {
        if ($x < 0 || $cdf < 0 || $cdf > 1) {
            throw new InvalidArgumentException('x deve ser >= 0 e CDF deve estar no intervalo [0, 1].');
        }

        $k = 1;  // Valor inicial para k
        $tolerance = 1.0e-6;
        $maxIterations = 1000;

        for ($i = 0; $i < $maxIterations; $i++) {
            $calculatedCDF = self::chisquare_cdf($x, $k);

            if (abs($calculatedCDF - $cdf) < $tolerance) {
                return $k;  // Encontrou k
            }

            // Ajuste do valor de k
            if ($calculatedCDF < $cdf) {
                $k += 0.1;
            } else {
                $k -= 0.1;
            }
        }

        throw new RuntimeException('Não foi possível encontrar k dentro do número máximo de iterações.');
    }

    protected static function exponential_cdf($x, $lambda)
    {
        if ($x < 0 || $lambda <= 0) {
            throw new InvalidArgumentException('x must be >= 0 and lambda > 0.');
        }
        return 1 - exp(-$lambda * $x);
    }

    protected static function find_exponential_x($cdf, $lambda)
    {
        if ($cdf < 0 || $cdf > 1 || $lambda <= 0) {
            throw new InvalidArgumentException('CDF deve estar no intervalo [0, 1] e lambda > 0.');
        }
        return -log(1 - $cdf) / $lambda;
    }

    protected static function find_exponential_lambda($x, $cdf)
    {
        if ($x < 0 || $cdf <= 0 || $cdf >= 1) {
            throw new InvalidArgumentException('x deve ser >= 0 e CDF deve estar no intervalo (0, 1).');
        }
        return -log(1 - $cdf) / $x;
    }

    protected static function f_cdf($x, $d1, $d2)
    {
        if ($x < 0 || $d1 <= 0 || $d2 <= 0) {
            throw new InvalidArgumentException('x deve ser >= 0 e d1, d2 > 0.');
        }

        // Usa a função beta incompleta regularizada para calcular a CDF
        $numerator = $d1 * $x;
        $denominator = $d1 * $x + $d2;

        return self::regularized_incomplete_beta($numerator / $denominator, $d1 / 2, $d2 / 2);
    }

    protected static function find_f_x($cdf, $d1, $d2)
    {
        if ($cdf < 0 || $cdf > 1 || $d1 <= 0 || $d2 <= 0) {
            throw new InvalidArgumentException('CDF deve estar no intervalo [0, 1] e d1, d2 > 0.');
        }

        $low = 0;
        $high = 100;  // Limite arbitrário para o valor de x
        $tolerance = 1.0e-6;

        while ($high - $low > $tolerance) {
            $mid = ($low + $high) / 2;
            $calculatedCDF = self::f_cdf($mid, $d1, $d2);

            if ($calculatedCDF < $cdf) {
                $low = $mid;
            } else {
                $high = $mid;
            }
        }

        return ($low + $high) / 2;
    }

    protected static function find_f_d1($x, $cdf, $d2)
    {
        if ($x < 0 || $cdf < 0 || $cdf > 1 || $d2 <= 0) {
            throw new InvalidArgumentException('x deve ser >= 0, CDF no intervalo [0, 1] e d2 > 0.');
        }

        $d1 = 1;  // Valor inicial para d1
        $tolerance = 1.0e-6;
        $maxIterations = 1000;

        for ($i = 0; $i < $maxIterations; $i++) {
            $calculatedCDF = self::f_cdf($x, $d1, $d2);

            if (abs($calculatedCDF - $cdf) < $tolerance) {
                return $d1;  // Encontrou d1
            }

            // Ajustar d1 com base na diferença
            if ($calculatedCDF < $cdf) {
                $d1 += 0.1;
            } else {
                $d1 -= 0.1;
            }
        }

        throw new RuntimeException('Não foi possível encontrar d1 dentro do número máximo de iterações.');
    }

    protected static function find_f_d2($x, $cdf, $d1)
    {
        if ($x < 0 || $cdf < 0 || $cdf > 1 || $d1 <= 0) {
            throw new InvalidArgumentException('x deve ser >= 0, CDF no intervalo [0, 1] e d1 > 0.');
        }

        $d2 = 1;  // Valor inicial para d2
        $tolerance = 1.0e-6;
        $maxIterations = 1000;

        for ($i = 0; $i < $maxIterations; $i++) {
            $calculatedCDF = self::f_cdf($x, $d1, $d2);

            if (abs($calculatedCDF - $cdf) < $tolerance) {
                return $d2;  // Encontrou d2
            }

            // Ajustar d2 com base na diferença
            if ($calculatedCDF < $cdf) {
                $d2 += 0.1;
            } else {
                $d2 -= 0.1;
            }
        }

        throw new RuntimeException('Não foi possível encontrar d2 dentro do número máximo de iterações.');
    }

    protected static function gamma_cdf($x, $k, $theta)
    {
        if ($x < 0 || $k <= 0 || $theta <= 0) {
            throw new InvalidArgumentException('x deve ser >= 0, k > 0 e theta > 0.');
        }

        return self::regularized_incomplete_gamma($x / $theta, $k);
    }

    protected static function regularized_incomplete_gamma($x, $a)
    {
        return self::incomplete_gamma($x, $a) / self::gamma($a);
    }

    // Função para calcular a função gama incompleta
    protected static function incomplete_gamma($x, $a)
    {
        $steps = 1000;  // Precisão da integração
        $dx = $x / $steps;
        $result = 0;

        for ($i = 0; $i <= $steps; $i++) {
            $t = $i * $dx;
            $result += pow($t, $a - 1) * exp(-$t) * $dx;
        }

        return $result;
    }

    // Função para encontrar x dado a CDF, k e theta
    protected static function find_gamma_x($cdf, $k, $theta)
    {
        if ($cdf < 0 || $cdf > 1 || $k <= 0 || $theta <= 0) {
            throw new InvalidArgumentException('CDF deve estar no intervalo [0, 1], k > 0 e theta > 0.');
        }

        $low = 0;
        $high = 100;  // Limite arbitrário para x
        $tolerance = 1.0e-6;

        while ($high - $low > $tolerance) {
            $mid = ($low + $high) / 2;
            $calculatedCDF = self::gamma_cdf($mid, $k, $theta);

            if ($calculatedCDF < $cdf) {
                $low = $mid;
            } else {
                $high = $mid;
            }
        }

        return ($low + $high) / 2;
    }

    // Função para encontrar k dado x, CDF e theta
    protected static function find_gamma_k($x, $cdf, $theta)
    {
        if ($x < 0 || $cdf < 0 || $cdf > 1 || $theta <= 0) {
            throw new InvalidArgumentException('x deve ser >= 0, CDF no intervalo [0, 1] e theta > 0.');
        }

        $k = 1;  // Valor inicial para k
        $tolerance = 1.0e-6;
        $maxIterations = 1000;

        for ($i = 0; $i < $maxIterations; $i++) {
            $calculatedCDF = self::gamma_cdf($x, $k, $theta);

            if (abs($calculatedCDF - $cdf) < $tolerance) {
                return $k;
            }

            // Ajuste do valor de k
            if ($calculatedCDF < $cdf) {
                $k += 0.1;
            } else {
                $k -= 0.1;
            }
        }

        throw new RuntimeException('Não foi possível encontrar k dentro do número máximo de iterações.');
    }

    // Função para encontrar theta dado x, CDF e k
    protected static function find_gamma_theta($x, $cdf, $k)
    {
        if ($x < 0 || $cdf < 0 || $cdf > 1 || $k <= 0) {
            throw new InvalidArgumentException('x deve ser >= 0, CDF no intervalo [0, 1] e k > 0.');
        }

        $theta = 1;  // Valor inicial para theta
        $tolerance = 1.0e-6;
        $maxIterations = 1000;

        for ($i = 0; $i < $maxIterations; $i++) {
            $calculatedCDF = self::gamma_cdf($x, $k, $theta);

            if (abs($calculatedCDF - $cdf) < $tolerance) {
                return $theta;
            }

            // Ajuste do valor de theta
            if ($calculatedCDF < $cdf) {
                $theta += 0.1;
            } else {
                $theta -= 0.1;
            }
        }

        throw new RuntimeException('Não foi possível encontrar theta dentro do número máximo de iterações.');
    }

    protected static function incomplete_beta($x, $a, $b)
    {
        if ($x < 0 || $x > 1) {
            throw new InvalidArgumentException('x deve estar no intervalo [0, 1].');
        }

        $steps = 1000;  // A precisão da integração pode ser ajustada com esse valor
        $dx = $x / $steps;
        $result = 0;

        for ($i = 0; $i <= $steps; $i++) {
            $t = $i * $dx;
            $result += pow($t, $a - 1) * pow(1 - $t, $b - 1) * $dx;
        }

        return $result;
    }

    protected static function regularized_incomplete_beta($x, $a, $b)
    {
        // Essa função pode ser implementada usando aproximações numéricas
        // Aqui está uma implementação simplificada
        return self::incomplete_beta($x, $a, $b) / self::beta_function($a, $b);
    }

    protected static function laplace_cdf($x, $mu, $b)
    {
        if ($b <= 0) {
            throw new InvalidArgumentException('O parâmetro b deve ser > 0.');
        }

        if ($x < $mu) {
            return 0.5 * exp(($x - $mu) / $b);
        } else {
            return 1 - 0.5 * exp(-($x - $mu) / $b);
        }
    }

    protected static function find_laplace_x($cdf, $mu, $b)
    {
        if ($cdf <= 0 || $cdf >= 1 || $b <= 0) {
            throw new InvalidArgumentException('CDF deve estar no intervalo (0, 1) e b > 0.');
        }

        if ($cdf < 0.5) {
            return $mu + $b * log(2 * $cdf);
        } else {
            return $mu - $b * log(2 * (1 - $cdf));
        }
    }

    protected static function find_laplace_mu($x, $cdf, $b)
    {
        if ($b <= 0 || $cdf <= 0 || $cdf >= 1) {
            throw new InvalidArgumentException('b deve ser > 0 e CDF no intervalo (0, 1).');
        }

        if ($cdf < 0.5) {
            return $x - $b * log(2 * $cdf);
        } else {
            return $x + $b * log(2 * (1 - $cdf));
        }
    }

    protected static function find_laplace_b($x, $cdf, $mu)
    {
        if ($cdf <= 0 || $cdf >= 1) {
            throw new InvalidArgumentException('CDF deve estar no intervalo (0, 1).');
        }

        if ($cdf < 0.5) {
            return ($x - $mu) / log(2 * $cdf);
        } else {
            return ($mu - $x) / log(2 * (1 - $cdf));
        }
    }

    protected static function logistic_cdf($x, $mu, $s)
    {
        if ($s <= 0) {
            throw new InvalidArgumentException('O parâmetro s deve ser > 0.');
        }

        return 1 / (1 + exp(-($x - $mu) / $s));
    }

    protected static function find_logistic_x($cdf, $mu, $s)
    {
        if ($cdf <= 0 || $cdf >= 1 || $s <= 0) {
            throw new InvalidArgumentException('O CDF deve estar no intervalo (0, 1) e s > 0.');
        }

        return $mu + $s * log($cdf / (1 - $cdf));
    }

    protected static function find_logistic_mu($x, $cdf, $s)
    {
        if ($cdf <= 0 || $cdf >= 1 || $s <= 0) {
            throw new InvalidArgumentException('O CDF deve estar no intervalo (0, 1) e s > 0.');
        }

        return $x - $s * log($cdf / (1 - $cdf));
    }

    protected static function find_logistic_s($x, $cdf, $mu)
    {
        if ($cdf <= 0 || $cdf >= 1) {
            throw new InvalidArgumentException('O CDF deve estar no intervalo (0, 1).');
        }

        return ($x - $mu) / log($cdf / (1 - $cdf));
    }

    protected static function negative_binomial_cdf($x, $r, $p)
    {
        if ($x < 0 || $r <= 0 || $p <= 0 || $p > 1) {
            throw new InvalidArgumentException('Os valores de x, r e p devem estar no intervalo correto.');
        }

        $cdf = 0;
        for ($k = 0; $k <= $x; $k++) {
            $cdf += self::negative_binomial_pmf($k, $r, $p);
        }
        return $cdf;
    }

    protected static function negative_binomial_pmf($x, $r, $p)
    {
        return self::binomial_coefficient($x + $r - 1, $x) * pow($p, $r) * pow(1 - $p, $x);
    }

    protected static function find_negative_binomial_x($cdf, $r, $p)
    {
        if ($cdf < 0 || $cdf > 1 || $r <= 0 || $p <= 0 || $p > 1) {
            throw new InvalidArgumentException('CDF deve estar no intervalo [0, 1], r > 0 e 0 < p <= 1.');
        }

        $x = 0;
        while (self::negative_binomial_cdf($x, $r, $p) < $cdf) {
            $x++;
        }
        return $x;
    }

    protected static function find_negative_binomial_r($x, $cdf, $p)
    {
        if ($x < 0 || $cdf < 0 || $cdf > 1 || $p <= 0 || $p > 1) {
            throw new InvalidArgumentException('x deve ser >= 0, CDF no intervalo [0, 1] e 0 < p <= 1.');
        }

        $r = 1;
        $tolerance = 1.0e-6;
        while (true) {
            $calculatedCDF = self::negative_binomial_cdf($x, $r, $p);
            if (abs($calculatedCDF - $cdf) < $tolerance) {
                return $r;
            }
            $r += 0.1;  // Ajuste iterativo
        }
    }

    protected static function find_negative_binomial_p($x, $cdf, $r)
    {
        if ($x < 0 || $cdf < 0 || $cdf > 1 || $r <= 0) {
            throw new InvalidArgumentException('x deve ser >= 0, CDF no intervalo [0, 1] e r > 0.');
        }

        $low = 0;
        $high = 1;
        $tolerance = 1.0e-6;

        while ($high - $low > $tolerance) {
            $mid = ($low + $high) / 2;
            $calculatedCDF = self::negative_binomial_cdf($x, $r, $mid);

            if ($calculatedCDF < $cdf) {
                $low = $mid;
            } else {
                $high = $mid;
            }
        }

        return ($low + $high) / 2;
    }

    protected static function noncentral_chisquare_cdf($x, $k, $lambda)
    {
        if ($x < 0 || $k <= 0 || $lambda < 0) {
            throw new InvalidArgumentException('Os valores devem atender: x >= 0, k > 0 e lambda >= 0.');
        }

        $sum = 0;
        $terms = 100;  // Número de termos para aproximação
        for ($i = 0; $i < $terms; $i++) {
            $poissonTerm = exp(-$lambda / 2) * pow($lambda / 2, $i) / stats_stat_factorial($i);
            $chiSquareTerm = self::central_chisquare_cdf($x, $k + 2 * $i);
            $sum += $poissonTerm * $chiSquareTerm;
        }

        return $sum;
    }

    // Função para calcular a CDF da distribuição qui-quadrado central
    protected static function central_chisquare_cdf($x, $k)
    {
        if ($x < 0 || $k <= 0) {
            throw new InvalidArgumentException('Os valores devem atender: x >= 0 e k > 0.');
        }

        return self::regularized_incomplete_gamma($x / 2, $k / 2);
    }

    protected static function find_noncentral_chisquare_x($cdf, $k, $lambda)
    {
        if ($cdf < 0 || $cdf > 1 || $k <= 0 || $lambda < 0) {
            throw new InvalidArgumentException('CDF deve estar no intervalo [0, 1], k > 0 e lambda >= 0.');
        }

        $low = 0;
        $high = 100;
        $tolerance = 1.0e-6;

        while ($high - $low > $tolerance) {
            $mid = ($low + $high) / 2;
            $calculatedCDF = self::noncentral_chisquare_cdf($mid, $k, $lambda);

            if ($calculatedCDF < $cdf) {
                $low = $mid;
            } else {
                $high = $mid;
            }
        }

        return ($low + $high) / 2;
    }

    protected static function find_noncentral_chisquare_k($x, $cdf, $lambda)
    {
        if ($x < 0 || $cdf < 0 || $cdf > 1 || $lambda < 0) {
            throw new InvalidArgumentException('x >= 0, CDF no intervalo [0, 1] e lambda >= 0.');
        }

        $k = 1;
        $tolerance = 1.0e-6;
        $maxIterations = 1000;

        for ($i = 0; $i < $maxIterations; $i++) {
            $calculatedCDF = self::noncentral_chisquare_cdf($x, $k, $lambda);

            if (abs($calculatedCDF - $cdf) < $tolerance) {
                return $k;
            }

            $k += 0.1;
        }

        throw new RuntimeException('Não foi possível encontrar k dentro do número máximo de iterações.');
    }

    protected static function find_noncentral_chisquare_lambda($x, $cdf, $k)
    {
        if ($x < 0 || $cdf < 0 || $cdf > 1 || $k <= 0) {
            throw new InvalidArgumentException('x >= 0, CDF no intervalo [0, 1] e k > 0.');
        }

        $lambda = 0;
        $tolerance = 1.0e-6;
        $maxIterations = 1000;

        for ($i = 0; $i < $maxIterations; $i++) {
            $calculatedCDF = self::noncentral_chisquare_cdf($x, $k, $lambda);

            if (abs($calculatedCDF - $cdf) < $tolerance) {
                return $lambda;
            }

            $lambda += 0.1;
        }

        throw new RuntimeException('Não foi possível encontrar lambda dentro do número máximo de iterações.');
    }

    protected static function noncentral_f_cdf($x, $nu1, $nu2, $lambda)
    {
        if ($x < 0 || $nu1 <= 0 || $nu2 <= 0 || $lambda < 0) {
            throw new InvalidArgumentException('Os valores devem atender: x >= 0, nu1 > 0, nu2 > 0 e lambda >= 0.');
        }

        $sum = 0;
        $terms = 100;  // Número de termos para aproximação
        for ($i = 0; $i < $terms; $i++) {
            $poissonTerm = exp(-$lambda / 2) * pow($lambda / 2, $i) / stats_stat_factorial($i);
            $fTerm = self::central_f_cdf($x, $nu1 + 2 * $i, $nu2);
            $sum += $poissonTerm * $fTerm;
        }

        return $sum;
    }

    protected static function central_f_cdf($x, $nu1, $nu2)
    {
        $beta = self::beta_function($nu1 / 2, $nu2 / 2);
        $sum = 0;
        $terms = 1000;
        $dx = $x / $terms;

        for ($i = 0; $i <= $terms; $i++) {
            $t = $i * $dx;
            $sum += pow($t, $nu1 / 2 - 1) * pow(1 + ($nu1 * $t) / $nu2, -($nu1 + $nu2) / 2) / $beta * $dx;
        }

        return $sum;
    }

    protected static function find_noncentral_f_x($cdf, $nu1, $nu2, $lambda)
    {
        if ($cdf < 0 || $cdf > 1 || $nu1 <= 0 || $nu2 <= 0 || $lambda < 0) {
            throw new InvalidArgumentException('CDF deve estar no intervalo [0, 1], nu1, nu2 > 0 e lambda >= 0.');
        }

        $low = 0;
        $high = 100;
        $tolerance = 1.0e-6;

        while ($high - $low > $tolerance) {
            $mid = ($low + $high) / 2;
            $calculatedCDF = self::noncentral_f_cdf($mid, $nu1, $nu2, $lambda);

            if ($calculatedCDF < $cdf) {
                $low = $mid;
            } else {
                $high = $mid;
            }
        }

        return ($low + $high) / 2;
    }

    protected static function find_noncentral_f_nu1($x, $cdf, $nu2, $lambda)
    {
        $nu1 = 1;
        $tolerance = 1.0e-6;

        while (true) {
            $calculatedCDF = self::noncentral_f_cdf($x, $nu1, $nu2, $lambda);
            if (abs($calculatedCDF - $cdf) < $tolerance) {
                return $nu1;
            }
            $nu1 += 0.1;
        }
    }

    protected static function find_noncentral_f_nu2($x, $cdf, $nu1, $lambda)
    {
        $nu2 = 1;
        $tolerance = 1.0e-6;

        while (true) {
            $calculatedCDF = self::noncentral_f_cdf($x, $nu1, $nu2, $lambda);
            if (abs($calculatedCDF - $cdf) < $tolerance) {
                return $nu2;
            }
            $nu2 += 0.1;
        }
    }

    protected static function find_noncentral_f_lambda($x, $cdf, $nu1, $nu2)
    {
        $lambda = 0;
        $tolerance = 1.0e-6;

        while (true) {
            $calculatedCDF = self::noncentral_f_cdf($x, $nu1, $nu2, $lambda);
            if (abs($calculatedCDF - $cdf) < $tolerance) {
                return $lambda;
            }
            $lambda += 0.1;
        }
    }

    protected static function noncentral_t_cdf($x, $nu, $mu)
    {
        if ($nu <= 0) {
            throw new InvalidArgumentException('O parâmetro nu deve ser > 0.');
        }

        $sum = 0;
        $terms = 100;  // Número de termos para aproximar a soma
        for ($i = 0; $i < $terms; $i++) {
            $poissonTerm = exp(-$mu * $mu / 2) * pow($mu * $mu / 2, $i) / stats_stat_factorial($i);
            $tTerm = self::central_t_cdf($x, $nu + 2 * $i);
            $sum += $poissonTerm * $tTerm;
        }

        return $sum;
    }

    protected static function central_t_cdf($x, $nu)
    {
        $beta = self::beta_function($nu / 2, 0.5);
        $integral = 0;
        $steps = 1000;
        $dx = $x / $steps;

        for ($i = 0; $i <= $steps; $i++) {
            $t = $i * $dx;
            $integral += pow(1 + $t * $t / $nu, -($nu + 1) / 2) / $beta * $dx;
        }

        return $integral;
    }

    protected static function find_noncentral_t_x($cdf, $nu, $mu)
    {
        if ($cdf < 0 || $cdf > 1 || $nu <= 0) {
            throw new InvalidArgumentException('O CDF deve estar no intervalo [0, 1] e nu > 0.');
        }

        $low = -100;
        $high = 100;
        $tolerance = 1.0e-6;

        while ($high - $low > $tolerance) {
            $mid = ($low + $high) / 2;
            $calculatedCDF = self::noncentral_t_cdf($mid, $nu, $mu);

            if ($calculatedCDF < $cdf) {
                $low = $mid;
            } else {
                $high = $mid;
            }
        }

        return ($low + $high) / 2;
    }

    // Função para calcular nu dado x, CDF e mu
    protected static function find_noncentral_t_nu($x, $cdf, $mu)
    {
        $nu = 1;
        $tolerance = 1.0e-6;

        while (true) {
            $calculatedCDF = self::noncentral_t_cdf($x, $nu, $mu);
            if (abs($calculatedCDF - $cdf) < $tolerance) {
                return $nu;
            }
            $nu += 0.1;
        }
    }

    // Função para calcular mu dado x, CDF e nu
    protected static function find_noncentral_t_mu($x, $cdf, $nu)
    {
        $mu = 0;
        $tolerance = 1.0e-6;

        while (true) {
            $calculatedCDF = self::noncentral_t_cdf($x, $nu, $mu);
            if (abs($calculatedCDF - $cdf) < $tolerance) {
                return $mu;
            }
            $mu += 0.1;
        }
    }

    protected static function normal_cdf($x, $mu, $sigma)
    {
        if ($sigma <= 0) {
            throw new InvalidArgumentException('O parâmetro sigma deve ser > 0.');
        }

        $z = ($x - $mu) / ($sigma * sqrt(2));
        return 0.5 * (1 + self::erf($z));
    }

    protected static function erf($z)
    {
        // Aproximação numérica para a função de erro
        $t = 1 / (1 + 0.3275911 * abs($z));
        $tau = $t * (
            0.254829592
            + $t * (-0.284496736
                + $t * (1.421413741
                    + $t * (-1.453152027
                        + $t * 1.061405429)))
        );

        $erf = 1 - $tau * exp(-$z * $z);
        return $z >= 0 ? $erf : -$erf;
    }

    protected static function find_normal_x($cdf, $mu, $sigma)
    {
        if ($cdf <= 0 || $cdf >= 1 || $sigma <= 0) {
            throw new InvalidArgumentException('CDF deve estar no intervalo (0, 1) e sigma > 0.');
        }

        $z = sqrt(2) * self::inverse_erf(2 * $cdf - 1);
        return $mu + $z * $sigma;
    }

    protected static function find_normal_mu($x, $cdf, $sigma)
    {
        if ($cdf <= 0 || $cdf >= 1 || $sigma <= 0) {
            throw new InvalidArgumentException('CDF deve estar no intervalo (0, 1) e sigma > 0.');
        }

        $z = sqrt(2) * self::inverse_erf(2 * $cdf - 1);
        return $x - $z * $sigma;
    }

    protected static function find_normal_sigma($x, $cdf, $mu)
    {
        if ($cdf <= 0 || $cdf >= 1) {
            throw new InvalidArgumentException('CDF deve estar no intervalo (0, 1).');
        }

        $z = sqrt(2) * self::inverse_erf(2 * $cdf - 1);
        return ($x - $mu) / $z;
    }

    protected static function inverse_erf($z)
    {
        // Aproximação numérica para a função inversa de erro
        $a = 0.147;  // Constante
        $sign = $z < 0 ? -1 : 1;
        $ln = log(1 - $z * $z);

        $inverse = $sign * sqrt(
            sqrt((2 / ($a * M_PI) + $ln / 2) ** 2 - $ln / $a) - (2 / ($a * M_PI) + $ln / 2)
        );

        return $inverse;
    }

    protected static function poisson_cdf($x, $lambda)
    {
        if ($x < 0 || $lambda <= 0) {
            throw new InvalidArgumentException('x deve ser >= 0 e lambda > 0.');
        }

        $sum = 0;
        for ($k = 0; $k <= $x; $k++) {
            $sum += self::poisson_pmf($k, $lambda);
        }
        return $sum;
    }

    protected static function poisson_pmf($x, $lambda)
    {
        return exp(-$lambda) * pow($lambda, $x) / stats_stat_factorial($x);
    }

    protected static function find_poisson_x($cdf, $lambda)
    {
        if ($cdf < 0 || $cdf > 1 || $lambda <= 0) {
            throw new InvalidArgumentException('CDF deve estar no intervalo [0, 1] e lambda > 0.');
        }

        $x = 0;
        while (self::poisson_cdf($x, $lambda) < $cdf) {
            $x++;
        }
        return $x;
    }

    protected static function find_poisson_lambda($x, $cdf)
    {
        if ($x < 0 || $cdf < 0 || $cdf > 1) {
            throw new InvalidArgumentException('x deve ser >= 0 e CDF no intervalo [0, 1].');
        }

        $lambda = 0.1;  // Começa com um valor pequeno para lambda
        $tolerance = 1.0e-6;

        while (true) {
            $calculatedCDF = self::poisson_cdf($x, $lambda);
            if (abs($calculatedCDF - $cdf) < $tolerance) {
                return $lambda;
            }
            $lambda += 0.01;
        }
    }

    protected static function t_cdf($x, $nu)
    {
        if ($nu <= 0) {
            throw new InvalidArgumentException('O parâmetro nu deve ser > 0.');
        }

        $beta = self::beta_function($nu / 2, 0.5);
        $integral = 0;
        $steps = 1000;
        $dx = $x / $steps;

        for ($i = 0; $i <= $steps; $i++) {
            $t = $i * $dx;
            $integral += pow(1 + $t * $t / $nu, -($nu + 1) / 2) / $beta * $dx;
        }

        return 0.5 + $integral;
    }

    // Função para calcular graus de liberdade (nu) dado x e CDF
    protected static function find_t_nu($x, $cdf)
    {
        $nu = 1;
        $tolerance = 1.0e-6;

        while (true) {
            $calculatedCDF = self::t_cdf($x, $nu);
            if (abs($calculatedCDF - $cdf) < $tolerance) {
                return $nu;
            }
            $nu += 0.1;  // Incrementa gradualmente para encontrar o valor
        }
    }

    // Função para calcular x dado a CDF e graus de liberdade (nu)
    protected static function find_t_x($cdf, $nu)
    {
        if ($cdf < 0 || $cdf > 1 || $nu <= 0) {
            throw new InvalidArgumentException('O CDF deve estar no intervalo [0, 1] e nu > 0.');
        }

        $low = -10;
        $high = 10;
        $tolerance = 1.0e-6;

        while ($high - $low > $tolerance) {
            $mid = ($low + $high) / 2;
            $calculatedCDF = self::t_cdf($mid, $nu);

            if ($calculatedCDF < $cdf) {
                $low = $mid;
            } else {
                $high = $mid;
            }
        }

        return ($low + $high) / 2;
    }

    protected static function uniform_cdf($x, $a, $b)
    {
        if ($a >= $b) {
            throw new InvalidArgumentException('O parâmetro "a" deve ser menor que "b".');
        }
        if ($x < $a) {
            return 0.0;
        } elseif ($x > $b) {
            return 1.0;
        } else {
            return ($x - $a) / ($b - $a);
        }
    }

    protected static function find_uniform_x($cdf, $a, $b)
    {
        if ($cdf < 0 || $cdf > 1 || $a >= $b) {
            throw new InvalidArgumentException('CDF deve estar no intervalo [0, 1] e "a" < "b".');
        }
        return $a + $cdf * ($b - $a);
    }

    protected static function find_uniform_a($x, $cdf, $b)
    {
        if ($cdf < 0 || $cdf > 1 || $x > $b) {
            throw new InvalidArgumentException('CDF deve estar no intervalo [0, 1] e x <= b.');
        }
        return $x - $cdf * ($b - $x) / (1 - $cdf);
    }

    protected static function find_uniform_b($x, $cdf, $a)
    {
        if ($cdf < 0 || $cdf > 1 || $x < $a) {
            throw new InvalidArgumentException('CDF deve estar no intervalo [0, 1] e x >= a.');
        }
        return $x + ($x - $a) / $cdf;
    }

    protected static function weibull_cdf($x, $k, $lambda)
    {
        if ($x < 0 || $k <= 0 || $lambda <= 0) {
            throw new InvalidArgumentException('x deve ser >= 0, k > 0 e lambda > 0.');
        }

        return 1 - exp(-pow($x / $lambda, $k));
    }

    protected static function find_weibull_x($cdf, $k, $lambda)
    {
        if ($cdf <= 0 || $cdf >= 1 || $k <= 0 || $lambda <= 0) {
            throw new InvalidArgumentException('CDF deve estar no intervalo (0, 1), k > 0 e lambda > 0.');
        }

        return $lambda * pow(-log(1 - $cdf), 1 / $k);
    }

    protected static function find_weibull_k($x, $cdf, $lambda)
    {
        if ($x < 0 || $cdf <= 0 || $cdf >= 1 || $lambda <= 0) {
            throw new InvalidArgumentException('x >= 0, CDF no intervalo (0, 1) e lambda > 0.');
        }

        $tolerance = 1.0e-6;
        $k = 1;  // Inicializa com um valor razoável
        $maxIterations = 1000;

        for ($i = 0; $i < $maxIterations; $i++) {
            $calculatedCDF = self::weibull_cdf($x, $k, $lambda);
            if (abs($calculatedCDF - $cdf) < $tolerance) {
                return $k;
            }
            $k += 0.1;
        }

        throw new RuntimeException('Não foi possível encontrar k dentro do número máximo de iterações.');
    }

    protected static function find_weibull_lambda($x, $cdf, $k)
    {
        if ($x < 0 || $cdf <= 0 || $cdf >= 1 || $k <= 0) {
            throw new InvalidArgumentException('x >= 0, CDF no intervalo (0, 1) e k > 0.');
        }

        return $x / pow(-log(1 - $cdf), 1 / $k);
    }
}
