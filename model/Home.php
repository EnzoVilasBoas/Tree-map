<?php
    class Home {
        /**
         * Função responsavel por retornar a lista dos jogos
         * @return array
         */
        public function listagem() {
            $url = "https://api.steampowered.com/ISteamChartsService/GetMostPlayedGames/v1";
            $retorno      = file_get_contents($url);
            $jogos = json_decode($retorno, true);
            $topJogos = array_slice($jogos['response']['ranks'], 0, 10);
            return $topJogos;
        }

        /**
         * Função responsavel por retornar os dados de um jogo especifico
         * @param int $appid
         */
        public function jogo($appid) {
            $url = "https://store.steampowered.com/api/appdetails?appids=$appid";
            $retorno      = file_get_contents($url);
            $dados = json_decode($retorno);
            return $dados;
        }

        /**
         * Função responsavel por retornar o numero total de jogadores
         * @return int
         */
        public function jogadores() {
            $jogos = $this->listagem();
            $total = array_sum(array_column($jogos, 'peak_in_game'));
            return $total;
        }

        /**
         * Função responsavel por identificar a porcentagem que o jogo representa
         * @param int $jogadores
         * @return string
         */
        public function porcentagem($jogadores) {
            $online = $this->jogadores();
            if ($online == 0) {
                return 0; // Evita divisão por zero
            }
            $porcentagem = ($jogadores / $online) * 100;
            return $porcentagem;
        }
    }