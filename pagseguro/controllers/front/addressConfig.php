<?php

class addressConfig {

    static function dados($v) {
        $dados = array();
        $dados['complementos'] = array("casa", "ap", "apto", "apart", "frente", "fundos", "sala", "cj");
        $dados['brasilias'] = array("bloco", "setor", "quadra", "lote");
        $dados['naobrasilias'] = array("av", "avenida", "rua", "alameda", "al.", "travessa", "trv", "praça", "praca");
        $dados['sems'] = array("sem ", "s.", "s/", "s. ", "s/ ");
        $dados['numeros'] = array('n.º', 'nº', "numero", "num", "número", "núm", "n");
        $dados['semnumeros'] = array();
        foreach ($dados['numeros'] as $n)
            foreach ($dados['sems'] as $s)
                $dados['semnumeros'][] = "$s$n";
        return $dados[$v];
    }

    static function endtrim($e) {
        return preg_replace('/^\W+|\W+$/', '', $e);
    }

    static function trataEndereco($end) {

        $endereco = $end;
        $numero = 's/nº';
        $complemento = '';
        $bairro = '';

        $quebrado = preg_split("/[-,\\n]/", $end);

        if (sizeof($quebrado) == 4) {
            list($endereco, $numero, $complemento, $bairro) = $quebrado;
        } elseif (sizeof($quebrado) == 3) {
            list($endereco, $numero, $complemento) = $quebrado;
        } elseif (sizeof($quebrado) == 2 || sizeof($quebrado) == 1) {
            list($endereco, $numero, $complemento) = self::ordenaDados($end);
        } else {
            $endereco = $end;
        }

        return array(self::endtrim(substr($endereco, 0, 69)), self::endtrim($numero), self::endtrim($complemento), self::endtrim($bairro));
    }

    static function ordenaDados($texto) {
        $quebrado = preg_split('/[-,\\n]/', $texto);

        for ($i = 0; $i < strlen($quebrado[0]); $i++) {
            if (is_numeric(substr($quebrado[0], $i, 1))) {
                return array(
                    substr($quebrado[0], 0, $i),
                    substr($quebrado[0], $i),
                    $quebrado[1]
                );
            }
        }

        $texto = preg_replace('/\s/', ' ', $texto);
        $encontrar = substr($texto, -strlen($texto));
        for ($i = 0; $i < strlen($texto); $i++) {
            if (is_numeric(substr($encontrar, $i, 1))) {
                return array(
                    substr($texto, 0, -strlen($texto) + $i),
                    substr($texto, -strlen($texto) + $i),
                    ''
                );
            }
        }
    }
}