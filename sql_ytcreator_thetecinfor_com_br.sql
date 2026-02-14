-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Tempo de geração: 10/02/2026 às 05:14
-- Versão do servidor: 11.4.4-MariaDB-log
-- Versão do PHP: 8.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `sql_ytcreator_thetecinfor_com_br`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbsys_access_plans`
--

CREATE TABLE `tbsys_access_plans` (
  `id` bigint(255) NOT NULL,
  `gcid` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `title` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `description` longtext NOT NULL,
  `observation` text DEFAULT NULL,
  `number_tokens` int(11) DEFAULT NULL,
  `number_scripts` int(11) DEFAULT NULL,
  `number_channels` int(11) DEFAULT NULL,
  `ribbon_tag` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `recommended` int(11) DEFAULT 0,
  `price` decimal(10,2) NOT NULL,
  `tax` decimal(10,2) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `date_start` date NOT NULL,
  `date_end` date NOT NULL,
  `validation` int(4) DEFAULT NULL,
  `status` int(1) NOT NULL,
  `user_id_created` bigint(255) DEFAULT NULL,
  `user_id_updated` bigint(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Despejando dados para a tabela `tbsys_access_plans`
--

INSERT INTO `tbsys_access_plans` (`id`, `gcid`, `title`, `description`, `observation`, `number_tokens`, `number_scripts`, `number_channels`, `ribbon_tag`, `recommended`, `price`, `tax`, `created_at`, `updated_at`, `date_start`, `date_end`, `validation`, `status`, `user_id_created`, `user_id_updated`) VALUES
(1, 'dc463058-f648-4350-aed9-fdae376846ea', 'Premium', 'Tudo do plano básico;Acesso a 1 (um) milhão de tokens de IA;Criação de Roteiros com IA;Analise de seus Roteiros com IA', 'Curta o melhor de tudo', 1000000, 99999999, 99999999, 'recomendado', 1, 59.90, 5.00, '2023-04-03 21:59:30', '2026-01-21 15:48:23', '2025-09-01', '2028-12-31', 31, 1, 1, 2),
(2, '3f6066ea-2eb2-4f6d-82f0-5a6a701c55e3', 'Básico', 'Cadastro de 2 Canais;Cadastro de 30 Roteiros Mensais;Acesso a Calendários de Postagens;Organização de Roteiros via kanban;Busca e Análise de Vídeos Virais no Youtube  ;Análises com IA *', '* Traga sua própria chave de IA (GEMINI)', 0, 30, 2, 'Promoção', 0, 29.90, 5.00, '2023-04-03 22:06:42', '2025-11-27 12:03:53', '2025-09-01', '2028-12-31', 31, 1, 1, 2),
(3, 'd88e8dbe-0847-46e6-a554-b154635dc66a', 'Vip', 'Tudo do Plano Premium;Acesso a 3 (três) milhões de tokens de IA;Cadastro de Canais Ilimitados;Roteiros Ilimitados', 'Seja um de nossos VIP\'S', 3000000, 99999999, 99999999, 'Promoção', 0, 99.90, NULL, '2025-09-01 08:46:19', '2025-11-27 12:05:44', '2025-09-01', '2028-12-31', 31, 1, 2, 2),
(4, 'f2ba9e06-b7d1-4c38-b35f-4276112991e1', 'Free', '7 dias de acesso gratuito para testar;Cadastro de 1 Canal;Criação de 5 Roteiros;5 Buscas e Análises de Vídeos Virais do Youtube;Calendário de Postagens;Organização dos Roteiros no kanban', '* teste nosso sistema por 7 dias gratuitamente', 1000, 5, 1, 'Teste grátis', 0, 0.00, NULL, '2025-11-27 09:05:53', '2025-12-29 22:46:04', '2025-11-27', '2028-12-31', 7, 1, 2, 2);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbsys_access_plans_coupons`
--

CREATE TABLE `tbsys_access_plans_coupons` (
  `id` bigint(255) NOT NULL,
  `gcid` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `coupon` varchar(100) NOT NULL,
  `discount` decimal(10,2) NOT NULL,
  `amount_use` int(5) DEFAULT NULL,
  `quantity_used` int(10) NOT NULL DEFAULT 0,
  `date_start` date NOT NULL,
  `date_end` date NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT 0,
  `user_id_created` bigint(255) DEFAULT NULL,
  `user_id_updated` bigint(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Despejando dados para a tabela `tbsys_access_plans_coupons`
--

INSERT INTO `tbsys_access_plans_coupons` (`id`, `gcid`, `coupon`, `discount`, `amount_use`, `quantity_used`, `date_start`, `date_end`, `created_at`, `updated_at`, `status`, `user_id_created`, `user_id_updated`) VALUES
(1, '0e50bf63-dfe8-42a1-a75d-6f0613456e28', 'FinanControl', 5.00, 50, 0, '2023-05-02', '2023-05-23', '2023-05-01 21:24:31', '2023-05-09 07:24:30', 1, 1, 1),
(2, '961a282b-506f-45f2-bde1-7d8a1e9b00a9', 'Finan', 5.00, 100, 0, '2025-09-03', '2025-09-30', '2023-05-02 21:31:21', '2025-09-03 10:39:44', 0, 1, 2),
(3, '6699379a-daa0-42e8-8a34-312d7f8452e4', 'br10', 10.00, 100, 0, '2025-09-03', '2026-12-31', '2025-09-03 09:57:15', '2025-12-31 12:00:26', 1, 2, 2);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbsys_access_plans_price`
--

CREATE TABLE `tbsys_access_plans_price` (
  `id` bigint(255) NOT NULL,
  `access_plan_id` bigint(255) NOT NULL,
  `currency_id` int(2) NOT NULL,
  `price` double(10,2) NOT NULL,
  `date_start` date DEFAULT NULL,
  `date_end` date DEFAULT NULL,
  `status` int(1) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `user_id_created` bigint(255) DEFAULT NULL,
  `user_id_updated` bigint(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Despejando dados para a tabela `tbsys_access_plans_price`
--

INSERT INTO `tbsys_access_plans_price` (`id`, `access_plan_id`, `currency_id`, `price`, `date_start`, `date_end`, `status`, `created_at`, `updated_at`, `user_id_created`, `user_id_updated`) VALUES
(1, 1, 2, 4.99, '2025-08-01', '2025-12-31', 1, '2025-08-30 11:35:12', '2025-12-29 15:44:36', 1, 2),
(2, 1, 1, 49.90, '2025-08-01', '2025-12-25', 1, '2025-08-31 15:07:28', '2025-11-27 08:44:05', 2, 2),
(3, 2, 2, 3.99, '2025-08-01', '2025-12-31', 1, '2025-08-31 15:08:20', '2025-08-31 15:09:35', 2, 2),
(4, 2, 1, 19.90, '2025-08-01', '2027-12-31', 1, '2025-08-31 15:08:41', '2025-11-27 08:54:18', 2, 2),
(5, 3, 1, 79.90, '2025-08-01', '2027-12-31', 1, '2025-09-01 08:46:49', '2025-12-31 11:59:49', 2, 2);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbsys_ai_preferences`
--

CREATE TABLE `tbsys_ai_preferences` (
  `id` bigint(255) NOT NULL,
  `gcid` text NOT NULL,
  `title` text DEFAULT NULL,
  `customer_gcid` text DEFAULT NULL,
  `channel_gcid` text DEFAULT NULL,
  `style` text DEFAULT NULL,
  `tone` varchar(255) DEFAULT NULL,
  `voice_rules` text DEFAULT NULL,
  `reference_channels` text DEFAULT NULL,
  `target_audience` varchar(255) DEFAULT NULL,
  `niche` varchar(255) DEFAULT NULL,
  `video_goal` varchar(255) DEFAULT NULL,
  `unique_value` text DEFAULT NULL,
  `brand_guidelines` text DEFAULT NULL,
  `video_length` varchar(255) DEFAULT NULL,
  `video_style` varchar(255) DEFAULT NULL,
  `editing_style` varchar(255) DEFAULT NULL,
  `hook_type` varchar(255) DEFAULT NULL,
  `cta_type` varchar(255) DEFAULT NULL,
  `analysis_type` varchar(255) DEFAULT NULL,
  `seo_focus` text DEFAULT NULL,
  `retention_focus` text DEFAULT NULL,
  `structure_rules` text DEFAULT NULL,
  `forbidden_words` text DEFAULT NULL,
  `priority_points` text DEFAULT NULL,
  `temperature` decimal(3,2) DEFAULT 0.70,
  `max_length` int(11) DEFAULT 500,
  `language_level` varchar(50) DEFAULT NULL,
  `additional_instructions` text DEFAULT NULL,
  `model` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Despejando dados para a tabela `tbsys_ai_preferences`
--

INSERT INTO `tbsys_ai_preferences` (`id`, `gcid`, `title`, `customer_gcid`, `channel_gcid`, `style`, `tone`, `voice_rules`, `reference_channels`, `target_audience`, `niche`, `video_goal`, `unique_value`, `brand_guidelines`, `video_length`, `video_style`, `editing_style`, `hook_type`, `cta_type`, `analysis_type`, `seo_focus`, `retention_focus`, `structure_rules`, `forbidden_words`, `priority_points`, `temperature`, `max_length`, `language_level`, `additional_instructions`, `model`, `created_at`, `updated_at`) VALUES
(1, 'c86fae89-8d6e-438c-9d66-23cdb9a82e6c', 'Canal de Animes', '1', '101', 'Explicativo + Engajado', 'Empolgado, jovem e dinâmico', 'Uso leve de humor otaku, linguagem simples e entusiasmo.', 'YukiAnime, OtakuWorld, Crunchyroll BR', 'Jovens entre 13 e 30 anos fãs de anime, mangá e cultura japonesa', 'Animes / Cultura otaku', 'Análises, explicações e curiosidades', 'Explicações claras, ritmo rápido e analogias divertidas', 'Cores vibrantes, evitar exageros ofensivos, manter respeito cultural', '8 a 12 min', 'Narrativo + Curiosidades', 'Cortes rápidos, zoom leve, legendas dinâmicas', 'Pergunta intrigante sobre personagem ou cena', 'Pedir inscrição destacando próximos vídeos de teorias', 'Completa (SEO + Retenção + Clareza)', 'Palavras-chave de animes, sagas, personagens e episódios populares', 'Manter ritmo rápido, evitar pausas longas e incluir curiosidades surpresa', 'Introdução curta → Contexto → Análise → Curiosidades → Fechamento', 'Palavrões, spoilers sem aviso', 'Clareza, Retenção, Engajamento, SEO', 0.60, 16000, 'Intermediário (fácil de entender)', 'Evitar spoilers diretos sem aviso; manter linguagem acessível.', 1, NULL, NULL),
(2, '1835adee-181a-4c35-b0d6-098588ab1429v', 'Canal de Reviews de Produtos', '1', '104', 'Objetivo + Informativo', 'Leve, honesto, transparente', 'Evitar exageros, ser imparcial e honesto nas análises.', 'Be!Tech, Loop Infinito, Marques Brownlee', 'Público geral 15–50 anos pesquisando produtos', 'Reviews de eletrônicos e gadgets', 'Avaliar produtos com clareza e honestidade', 'Comparações claras, testes reais e opinião justa', 'Layout limpo, foco em mostrar o produto', '8 a 12 min', 'Review com pontos positivos e negativos', 'Cortes rápidos, close em produto, texto na tela', 'Apresentar defeito ou qualidade surpreendente logo no início', 'CTA para review completo ou comparativo', 'Completa (SEO + Clareza + Retenção)', 'Palavras-chave sobre produto, specs e comparação com concorrentes', 'Mostrar testes reais cedo, incluir cortes dinâmicos e gráficos simples', 'Introdução → Pontos Positivos → Pontos Negativos → Conclusão', 'Promessas falsas, linguagem agressiva', 'Transparência, Utilidade, Clareza, Honestidade', 0.50, 14000, 'Básico a intermediário', 'Evitar parecer patrocinado quando não for.', 1, NULL, NULL),
(3, 'c7cd330a-d049-476a-b354-37cb523fafad', 'Canal de Negócios / Empreendedorismo', '1', '103', 'Profissional + Educativo', 'Confiante, motivador e direto', 'Evitar promessas milagrosas e clichês; foco em dados.', 'Primo Rico, Insights de Negócios, Gestão 4.0', 'Empreendedores iniciantes 20–45 anos', 'Negócios / Marketing / Empreendedorismo', 'Educação, estratégia e insights para crescer negócios', 'Abordagem prática com exemplos reais e frameworks', 'Design clean, foco em seriedade e credibilidade', '8 a 12 min', 'Educacional com exemplos práticos', 'Cortes limpos, gráficos, tela dividida ocasional', 'Promessa de transformação (ex: \"Como dobrar suas vendas…\")', 'CTA para material gratuito ou comunidade', 'Análise estratégica com foco em clareza', 'Palavras-chave de negócios, marketing, vendas e produtividade', 'Incluir frameworks visuais, checkpoints e resumos curtos durante o vídeo', 'Introdução → Problema → Solução → Framework → CTA', 'Promessas milagrosas, termos ilegais, ganhos garantidos', 'Autoridade, Clareza, Praticidade, SEO', 0.50, 15000, 'Intermediário', 'Evitar falar de investimentos como garantia; evitar riscos legais.', 1, NULL, NULL),
(4, 'c7cd330a-d049-476a-b354-37cb523fafadv', 'Canal de Games', '1', '201', 'Dinâmico + Divertido', 'Empolgado, gamer e descontraído', 'Usar expressões leves gamer, evitar exageros e toxicidade.', 'Hayashii, BRKsEDU, The Enemy', 'Jovens 12–35 apaixonados por jogos', 'Games / Gameplay / Reviews', 'Entreter, analisar e mostrar gameplay real', 'Opinião honesta com momentos divertidos', 'Visual vibrante, cortes rápidos, sem linguagem ofensiva', '8 a 12 min', 'Gameplay comentado + Review', 'Cortes rápidos, zooms, momentos engraçados', 'Highlight de cena épica ou bug engraçado', 'CTA leve pedindo inscrição e envio de sugestões de jogos', 'Completa (SEO + Retenção)', 'Palavras-chave de jogos, mecânicas, análises e gameplay', 'Inserir momentos surpreendentes, cortes rápidos e reações naturais', 'Introdução → Gameplay chave → Pontos fortes → Pontos fracos → Conclusão', 'Toxicidade, xingamentos, discurso de ódio', 'Entretenimento, Clareza, Ritmo, Humor', 0.70, 16000, 'Intermediário', 'Sempre avisar quando houver spoilers ou conteúdo sensível.', 1, NULL, NULL),
(5, '66e00acd-4095-49cc-9624-65eacdd59c79', 'Canal de Tecnologia', '1', '202', 'Tecnológico + Claro', 'Sério, técnico e acessível', 'Explicar termos complexos de forma simples; evitar jargão profundo.', 'Loop Infinito, Canaltech, Marques Brownlee', 'Adultos 18–45 interessados em tecnologia', 'Tecnologia / Gadgets / Inovações', 'Educar e apresentar novidades com clareza', 'Explicações técnicas fáceis e honestas', 'Design minimalista, linguagem profissional', '8 a 12 min', 'Review + Demonstração prática', 'Transições clean, sobreposição de textos, gráficos simples', 'Destaque imediato do diferencial do gadget', 'CTA para comparativos ou lista completa de specs', 'Análise técnica com foco em clareza', 'Palavras-chave técnicas do produto, specs e comparações', 'Começar com diferencial do produto e usar gráficos para dados complexos', 'Introdução → Funcionalidades → Testes → Conclusão', 'Promessas falsas, sensacionalismo', 'Transparência, Tecnologia, Clareza, Precisão', 0.40, 18000, 'Intermediário', 'Evitar recomendações sem testes reais; sempre comparar com concorrentes.', 1, NULL, NULL),
(6, 'bb3b4a76-3a20-4fa3-8bd2-7fd0c60d5d4f', 'Canal Financeiro', '1', '203', 'Profissional + Didático', 'Confiante, responsável e educativo', 'Evitar promessas de resultados; usar dados reais e alertas de risco.', 'Primo Rico, Me Poupe!, Você MAIS Rico', 'Adultos 20–50 buscando educação financeira', 'Finanças / Investimentos / Economia pessoal', 'Educar sobre dinheiro de forma segura e realista', 'Explicações com exemplos práticos e simuladores', 'Tom sério, evitar sensacionalismo, foco em responsabilidade', '8 a 12 min', 'Educacional com frameworks financeiros', 'Cortes limpos, gráficos financeiros, números na tela', 'Frase forte sobre dinheiro ou erro comum', 'CTA para planilha gratuita ou vídeo complementar', 'Análise responsável com foco em clareza', 'Palavras-chave financeiras, temas de economia e investimentos', 'Inserir avisos de risco, ritmo constante e explicações com exemplos reais', 'Introdução → Problema → Solução → Exemplos → Aviso ético → Conclusão', 'Promessas de enriquecimento rápido, irregularidades financeiras', 'Responsabilidade, Dados, Clareza, Ética', 0.30, 20000, 'Intermediário', 'Sempre incluir avisos de risco; evitar recomendações diretas de investimento.', 1, NULL, NULL),
(7, 'c225053e-6202-459e-91ae-23a38b61d7c0', 'Canal de Filmes / Cinema', '1', '102', 'Crítico + Cineasta', 'Sério, elegante e analítico', 'Evitar gírias, usar termos técnicos simples de cinema.', 'Pipocando, Omelete, Cinema com Rapadura', 'Adultos 18–45 que gostam de críticas e análises profundas', 'Críticas de filmes / cinema', 'Análise técnica e narrativa de filmes', 'Abordagem profissional com explicações claras e imparciais', 'Visual minimalista, linguagem clara, evitar sensacionalismo', '10-15 minutos', 'Análise detalhada + Comentário cultural', 'Cortes suaves, transições elegantes, trilha leve', 'Pergunta retórica sobre roteiro ou direção', 'CTA elegante pedindo opinião nos comentários', 'Análise crítica orientada a estrutura', 'Títulos com nome dos filmes + temas relevantes', 'Manter narrativa fluida, boa ambientação e ritmo coerente', 'Introdução → Contexto → Fotografia → Roteiro → Conclusão', 'Gírias, opiniões ofensivas, spoilers sem aviso', 'Profundidade, Análise, Clareza, SEO', 0.40, 20000, 'Avançado moderado', 'Evitar julgamentos agressivos; manter tom respeitoso.', 1, NULL, NULL),
(8, 'ab44fa36-9192-4894-bced-f470abaa5e1d', NULL, '7192e5a9-f495-4951-89a1-ff5c285256f1', 'aaa6c22d-3e2a-420d-8a08-d98b729dfdb2', 'Explicativo + Engajado', 'Empolgado, jovem e dinâmico', 'Uso leve de humor otaku, linguagem simples e entusiasmo.', 'YukiAnime, OtakuWorld, Crunchyroll BR', 'Jovens entre 13 e 30 anos fãs de anime, mangá e cultura japonesa', 'Animes / Cultura otaku', 'Análises, explicações e curiosidades', 'Explicações claras, ritmo rápido e analogias divertidas', 'Cores vibrantes, evitar exageros ofensivos, manter respeito cultural', '8', 'Narrativo + Curiosidades', 'Cortes rápidos, zoom leve, legendas dinâmicas', 'Pergunta intrigante sobre personagem ou cena', 'Pedir inscrição destacando próximos vídeos de teorias', 'Completa (SEO + Retenção + Clareza)', 'Palavras-chave de animes, sagas, personagens e episódios populares', 'Manter ritmo rápido, evitar pausas longas e incluir curiosidades surpresa', 'Introdução curta → Contexto → Análise → Curiosidades → Fechamento', 'Palavrões, spoilers sem aviso', 'Clareza, Retenção, Engajamento, SEO', 0.60, 16000, 'Intermediário (fácil de entender)', 'Evitar spoilers diretos sem aviso; manter linguagem acessível.', 0, '2025-11-24 22:41:30', '2025-12-06 21:29:28'),
(9, 'e7982809-c97e-4a8c-87c8-e2c83b33c9a4', NULL, '7192e5a9-f495-4951-89a1-ff5c285256f1', '27bb28cd-d53b-4f8f-b0b4-44bb8da4ef24', 'Explicativo + Engajado', 'Empolgado, jovem e dinâmico', 'Uso leve de humor otaku, linguagem simples e entusiasmo.', 'YukiAnime, OtakuWorld, Crunchyroll BR', 'Jovens entre 13 e 30 anos fãs de anime, mangá e cultura japonesa', 'Animes / Cultura otaku', 'Análises, explicações e curiosidades', 'Explicações claras, ritmo rápido e analogias divertidas', 'Cores vibrantes, evitar exageros ofensivos, manter respeito cultural', '10', 'Narrativo + Curiosidades', 'Cortes rápidos, zoom leve, legendas dinâmicas', 'Pergunta intrigante sobre personagem ou cena', 'Pedir inscrição destacando próximos vídeos de teorias', 'Completa (SEO + Retenção + Clareza)', 'Palavras-chave de animes, sagas, personagens e episódios populares', 'Manter ritmo rápido, evitar pausas longas e incluir curiosidades surpresa', 'Introdução curta → Contexto → Análise → Curiosidades → Fechamento', 'Palavrões, spoilers sem aviso', 'Clareza, Retenção, Engajamento, SEO', 0.75, 16000, 'Intermediário (fácil de entender)', 'Evitar spoilers diretos sem aviso; manter linguagem acessível.', 0, NULL, '2026-02-08 21:09:59');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbsys_clientloginattempts`
--

CREATE TABLE `tbsys_clientloginattempts` (
  `client_id` bigint(255) NOT NULL,
  `time` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbsys_cloudflare_api`
--

CREATE TABLE `tbsys_cloudflare_api` (
  `id` int(1) NOT NULL,
  `user_id_updated` bigint(255) NOT NULL,
  `cust_email` text NOT NULL,
  `cust_xauth` text NOT NULL,
  `cust_domain` text NOT NULL,
  `cust_zone` text NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Despejando dados para a tabela `tbsys_cloudflare_api`
--

INSERT INTO `tbsys_cloudflare_api` (`id`, `user_id_updated`, `cust_email`, `cust_xauth`, `cust_domain`, `cust_zone`, `created_at`) VALUES
(1, 1, 'thetecinfor@gmail.com', 'nyO1Hr_qoJZ3rtIwqWTeYPxT6U_lVPV3aM2kRS7d', 'thetecinfor.com.br', 'c70800dbb50a8100ac918ee2eb7ba2a1', '2025-11-17 23:04:22');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbsys_company`
--

CREATE TABLE `tbsys_company` (
  `id` int(1) NOT NULL,
  `user_id_updated` bigint(255) DEFAULT NULL,
  `name_company` varchar(100) DEFAULT NULL,
  `name_fantasy` varchar(100) DEFAULT NULL,
  `cnpj` varchar(18) DEFAULT NULL,
  `municipal_registration` varchar(50) DEFAULT NULL,
  `state_registration` varchar(50) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `contact` varchar(20) DEFAULT NULL,
  `logo` text DEFAULT NULL,
  `opening` date DEFAULT NULL,
  `andress_cep` varchar(8) DEFAULT NULL,
  `andress_street` varchar(100) DEFAULT NULL,
  `andress_number` varchar(10) DEFAULT NULL,
  `andress_complement` varchar(30) DEFAULT NULL,
  `andress_neighbhood` varchar(50) DEFAULT NULL,
  `andress_city` varchar(50) DEFAULT NULL,
  `andress_state` varchar(50) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Despejando dados para a tabela `tbsys_company`
--

INSERT INTO `tbsys_company` (`id`, `user_id_updated`, `name_company`, `name_fantasy`, `cnpj`, `municipal_registration`, `state_registration`, `email`, `contact`, `logo`, `opening`, `andress_cep`, `andress_street`, `andress_number`, `andress_complement`, `andress_neighbhood`, `andress_city`, `andress_state`, `updated_at`) VALUES
(1, 2, 'A R Gomes', 'Sua Marca', '01841729000153', '1234567890', '', 'ricardogssa@gmail.com', '71999998888', 'logodesignclipartpng0.jpg', '2025-09-04', '41213000', 'Avenida Ulysses Guimarães', '151', 'Bloco2', 'Sussuarana', 'Salvador', 'Bahia', '2025-10-28 09:17:09');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbsys_cron_emails`
--

CREATE TABLE `tbsys_cron_emails` (
  `id` bigint(255) NOT NULL,
  `status` int(1) NOT NULL,
  `email` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `nameMailer` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `subject` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `messageSend` text NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbsys_cron_emails_files`
--

CREATE TABLE `tbsys_cron_emails_files` (
  `id` bigint(255) NOT NULL,
  `status` int(1) NOT NULL,
  `email` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `nameMailer` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `subject` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `messageSend` text NOT NULL,
  `files` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbsys_cron_emails_nfse`
--

CREATE TABLE `tbsys_cron_emails_nfse` (
  `id` bigint(255) NOT NULL,
  `signature_payment_gcid` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `status` int(1) NOT NULL,
  `email` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `nameMailer` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `subject` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `messageSend` text NOT NULL,
  `files` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbsys_currency`
--

CREATE TABLE `tbsys_currency` (
  `id` int(2) NOT NULL,
  `title` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `currency` varchar(8) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `locale` varchar(8) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `placeholder` varchar(15) DEFAULT NULL,
  `status` int(1) NOT NULL,
  `active` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Despejando dados para a tabela `tbsys_currency`
--

INSERT INTO `tbsys_currency` (`id`, `title`, `currency`, `locale`, `placeholder`, `status`, `active`) VALUES
(1, 'Real brasileiro', 'BRL', 'pt_BR', 'R$ 0,00', 1, 1),
(2, 'Dólar americano', 'USD', 'en_US', '$0.00', 1, 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbsys_customer`
--

CREATE TABLE `tbsys_customer` (
  `id` bigint(255) NOT NULL,
  `google_id` varchar(255) DEFAULT NULL,
  `gcid` char(50) NOT NULL,
  `language_id` int(3) DEFAULT NULL,
  `photo` text DEFAULT NULL,
  `google_photo` text DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `cpf` varchar(14) DEFAULT NULL,
  `birth` date DEFAULT NULL,
  `gender` int(1) DEFAULT NULL,
  `andress_city` varchar(50) DEFAULT NULL,
  `andress_state` varchar(50) DEFAULT NULL,
  `andress_avenue` varchar(50) DEFAULT NULL,
  `andress_neighborhood` varchar(50) DEFAULT NULL,
  `andress_complement` varchar(50) DEFAULT NULL,
  `andress_number` varchar(10) DEFAULT NULL,
  `andress_cep` varchar(8) DEFAULT NULL,
  `contact` varchar(20) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `passwd` text NOT NULL,
  `salt` text NOT NULL,
  `session_date` datetime DEFAULT NULL,
  `session_date_last` datetime DEFAULT NULL,
  `token` text DEFAULT NULL,
  `token_date` datetime DEFAULT NULL,
  `code` text DEFAULT NULL,
  `auth_token` text DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `date_start` datetime DEFAULT NULL,
  `date_end` datetime DEFAULT NULL,
  `status` int(1) NOT NULL,
  `is_premium` int(1) DEFAULT 0,
  `terms` int(1) DEFAULT 0,
  `public_key` text DEFAULT NULL,
  `private_key` text DEFAULT NULL,
  `aes` text DEFAULT NULL,
  `token_ai` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Despejando dados para a tabela `tbsys_customer`
--

INSERT INTO `tbsys_customer` (`id`, `google_id`, `gcid`, `language_id`, `photo`, `google_photo`, `name`, `cpf`, `birth`, `gender`, `andress_city`, `andress_state`, `andress_avenue`, `andress_neighborhood`, `andress_complement`, `andress_number`, `andress_cep`, `contact`, `email`, `passwd`, `salt`, `session_date`, `session_date_last`, `token`, `token_date`, `code`, `auth_token`, `created_at`, `updated_at`, `date_start`, `date_end`, `status`, `is_premium`, `terms`, `public_key`, `private_key`, `aes`, `token_ai`) VALUES
(1, '117392030458782381663', '7192e5a9-f495-4951-89a1-ff5c285256f1', 1, 'images.jpg', 'https://lh3.googleusercontent.com/a/ACg8ocJ6VdMlzuGfoxYLWHG4PGGQwdTpRYh8QKSGCFhGW-fPPQDgmg=s96-c', 'Andre Ricardo', '03456150504', '1991-02-18', NULL, 'Salvador', 'Bahia', 'Avenida Ulysses Guimarães', 'Sussuarana', NULL, '151', '41213000', '(71) 99990-3997', 'ricardogssa@gmail.com', 'c90e1e30511c96607724d3118650491071b81429e3338e9f001b4df9a78d55b3e672e6ddef549fb8968cab9ec819c3363313367165cf0960b3addef8fe987dbc', '1266761ad5303d5ebdefe167848a7324dfef2af8dbba448ec5bd543c5ddf13bbf30712f300287680eb44a3a35cb08e2e1d867ac55b1669a22e634d16b5295edb', '2026-02-09 17:11:12', '2026-02-09 17:11:12', NULL, NULL, NULL, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJnY2lkIjoiNzE5MmU1YTktZjQ5NS00OTUxLTg5YTEtZmY1YzI4NTI1NmYxIiwibmFtZSI6IkFuZHJlIFJpY2FyZG8iLCJlbWFpbCI6InJpY2FyZG9nc3NhQGdtYWlsLmNvbSIsImV4cCI6MTc2MzM4MzM2Mn0.qdppYlMSL6Xat7GIP_YZJQGGfnSv-z79xWPJ5ZOuzvk', '2025-09-19 23:15:42', '2026-02-09 17:11:12', '2025-10-17 23:13:39', '2025-11-16 23:13:39', 1, 1, 1, 'LS0tLS1CRUdJTiBQVUJMSUMgS0VZLS0tLS0KTUlJQ0lqQU5CZ2txaGtpRzl3MEJBUUVGQUFPQ0FnOEFNSUlDQ2dLQ0FnRUFtUmFUWHhvQWxUWFI4ZDM5KzBMbApsUVNYME5GUjBiQjlGMmg4TFkyK1p5b25qTkhZQVY3cVcreFpIWlM1bE1HVHRFeXQzSGQvdTlQcUYrMWJBMDhWClA1SDFTNzVlYitYTHdPTU02bVZlSUNUNVB1UTlnNENVN01XMGVrWXdDc2E5c3N3Ukk3dWlpV2NOMUlxTkd1Q2wKWUtSSUg0bGlkd3VEWXFsTjdtZXYrSFNVaDBKNDc1QVlxUGZMb3BJK3E5aGt2dEtQMy9RNFBpekEzdldONlhodAowSUNuUjFMa1hidXgwcHJiaFA1cG5FbDJQWnBOR2QyZGxBQ3czN2ZNbnQvUS9yTmUvR1c2Vjh6alpuZVVYbUx2CnlRdW53NXNid21DUUk0TDhlRmxRNWNxM1piUmpRSnRyU3VpdEhqR05pLyt1SW54V0pMWGhsMnpOYTZSTHVzdUMKaHpqOUZHZ2pxalh3TTV5dkVpQlVwMEtsUHYxd0xTL1pxSTZXamxSR0JqaURkNFA2UXFheVhxZFhYLy9HeVVrUQorM2ljdXNyR2ZiVU45V0RQejdibndTdzBQR0ZNdktqb0greEJ2d1dGY2xEUGdPTkZoOTIzZ1FMZHVvSDJlRktBClBtNmpLbVVxVm95RzBidHAyL00xaTEvRmpoSnBPVEtaQVdsdzlQSW5QNFFzaFpNNVVySWxRbjBWU3lXdnNOeE8KTjIvZG1INDZ5ZlY2RzZaRm1nWm02aGcvRFhsT29qaEYzdnhoWWQxZlRyYXUxYnlRQnBJTnRuVVhrb3c3WHY3LwpjeElXc1VCcEh4eUZlYjVVblFKLzFkcjhlWVM5Q05RdER2Zm0vbUJoM3hITlprS0N0SjdMbWZ3eEthRUg5WUlFCnlNb0xvRnFYY01XZUJQbEtNY2ROV3NjQ0F3RUFBUT09Ci0tLS0tRU5EIFBVQkxJQyBLRVktLS0tLQo=', 'LS0tLS1CRUdJTiBQUklWQVRFIEtFWS0tLS0tCk1JSUpRZ0lCQURBTkJna3Foa2lHOXcwQkFRRUZBQVNDQ1N3d2dna29BZ0VBQW9JQ0FRQ1pGcE5mR2dDVk5kSHgKM2YzN1F1V1ZCSmZRMFZIUnNIMFhhSHd0amI1bktpZU0wZGdCWHVwYjdGa2RsTG1Vd1pPMFRLM2NkMys3MCtvWAo3VnNEVHhVL2tmVkx2bDV2NWN2QTR3enFaVjRnSlBrKzVEMkRnSlRzeGJSNlJqQUt4cjJ5ekJFanU2S0padzNVCmlvMGE0S1ZncEVnZmlXSjNDNE5pcVUzdVo2LzRkSlNIUW5qdmtCaW85OHVpa2o2cjJHUyswby9mOURnK0xNRGUKOVkzcGVHM1FnS2RIVXVSZHU3SFNtdHVFL21tY1NYWTltazBaM1oyVUFMRGZ0OHllMzlEK3MxNzhaYnBYek9ObQpkNVJlWXUvSkM2ZkRteHZDWUpBamd2eDRXVkRseXJkbHRHTkFtMnRLNkswZU1ZMkwvNjRpZkZZa3RlR1hiTTFyCnBFdTZ5NEtIT1AwVWFDT3FOZkF6bks4U0lGU25RcVUrL1hBdEw5bW9qcGFPVkVZR09JTjNnL3BDcHJKZXAxZGYKLzhiSlNSRDdlSnk2eXNaOXRRMzFZTS9QdHVmQkxEUThZVXk4cU9nZjdFRy9CWVZ5VU0rQTQwV0gzYmVCQXQyNgpnZlo0VW9BK2JxTXFaU3BXakliUnUybmI4eldMWDhXT0VtazVNcGtCYVhEMDhpYy9oQ3lGa3psU3NpVkNmUlZMCkphK3czRTQzYjkyWWZqcko5WG9icGtXYUJtYnFHRDhOZVU2aU9FWGUvR0ZoM1Y5T3RxN1Z2SkFHa2cyMmRSZVMKakR0ZS92OXpFaGF4UUdrZkhJVjV2bFNkQW4vVjJ2eDVoTDBJMUMwTzkrYitZR0hmRWMxbVFvSzBuc3VaL0RFcApvUWYxZ2dUSXlndWdXcGR3eFo0RStVb3h4MDFheHdJREFRQUJBb0lDQUVSRTV3K2dIdVpyaSthYW91cnNHRW51Ck5HMnhDeFhCNk9jSmQyY3hNTm44MklwYUFrUUtPZVVvYjAxYng2N283SitaR21lWSt1T2VTMlRFT3JRdERrSzkKS25ET3ducVFOZDhjNGVPZHRPNE16d0lXOHIrMEZiWEpMUVRpVEFaaVBySi9ncDAyemZNTWZBUnVqU0tSVCs3YgpGRGJNSTVjSEVWNXNOZzY5T3FKSUN2eU96ak8zUk9nRktWQ2tlMEpUVEFvMUNHaE5Gcy9UVVdlY2hkNjZEKzdOCmtNdWowYWRqVHBlbTY0SlJtbk5SNTJMdGJyaThOY3VNeTFQWk9XMFlUckZtK0ZNQ1lxbEkrNWYwZDd2bnp4c1AKRVkrUG1qK0NwSVRSb3hsZ2EwMHVxNzYxUlJYSXVYNDhhUWR2L2JtVEdlclRHKzlmRk8xY1hmZEUyMmkvM01NVQpNOUZ4a09QWEtKTGV5WEl0RTA3aUVhWXdnbWZNSTRhbzgvbGxWSWVNZzRBMjU0d1RtbTk5SkJqVTJveGltSjk2CmVmK0IycWdFNTlOQWRzV2hFWmdYZmFzZlhDR09nUE5pdy9JVGdJdWJwR3VOVTk5RW9RTkpHQWl6ZmVkUkFhKzEKS1pDZ3lSWXQ3M2l4YlRraEpFWks1a2dRRUtUdmxaUUpEN1JIbDI5UVBGTjFRWDhWWlRwVFJRem1ZOUxqSlBROApjZ1VHU1BoeDdBRU5KZlRaYjZ2U3RFQy93WDd5VVBudU1Rd0JoVU9rYlp5Z2cvUXZQbFBPZVdlZkgxR2UyV0g4ClVHYTNKak43ZTZ3L3hJYUYvaFlkS0dxRXA2Y1VPdjVsYTFhZmtZeHFKd3JqMHhzN2hYc0xHQjhMOEJ5cmp3QzMKMHFRa0phOG9GUzRnUVQzOUxSSWhBb0lCQVFESnRUaEdBanoxNVZiMnJncFV6ZHE5MjJFMjZGelFZVXlxZHUwaQoyYitHTXN6SFl3T0RrSUEwaG11YkJRTTQ0UVk4MUlvU0NGVXBvK3V0bGVoMnhnV0ZyUm9zdnJOdVc1TElLYVVXCmVCNHQyeXdNZFFzbGlMalFJYmhoajVWUE4yZ2szVnliMVdSTFA4MUpPeXFWR3dKR0JLUjM5TlVuZS9od28vZHcKai9OdUdBUXVCK1RKUnZTK21tREp5Mmd2Wk13cFhhZWdMRkVkbjdMMG1DZG13aW5NbVVtVC9lUHlvN2wwbFpvWQpmMUJBUEtSWXdTNC8weVVHNkpUNGJzN0hrb1hFcDdNWVU5SUJVQTQ1UXh0WUR0TENCZ0xjdDg5ZTA1aVNlS0NwCk0zR3JkVGVtc1pTVWlVcklCMGh0RXJOM0xuY212ZWdRc0xDc1pFcGFoaUVMWXRmZkFvSUJBUURDU3pFSXVjR3cKaXlhVkx2QTZWaVpsRXp0dHhwMlpZQTIzL1EvenVSSDVTcDNiWDRkYlJtdTQrMmNQOUYvTC92VW5ZSnBLVWtNYgpVRkd5LzR4Q2t0dTN0endFempkaWdTN0ZRUWoyaTJzTW5pNkhMZjhiT3hBM1pudnJuT2crTU9JQUx1WnFJMU0yCk5XeFM0SmIvczJ6OWl5T3NzL0NPK01aWHhZaDRaVzVrQVphR3hKbTd4Y3dyK2lia0NsSzlmRjd5a05Ea3FUNHUKMUUzUGMwMnFJZmFpa3dybUZZWXBVdXpVY3ZXRjFPMzJlcGUzRmloRURUejNTcGxkdGZVeFNRR2s3NUlXaFlDcQptekFyNk1GdDIwdjdVUW1wMTBJQ0xtVnF3bFVrRitHSWxLVzEyWUhnVUNsa3U0dklFV3d4OFJGRVpLYndOUm1SCmZQSTZ0SFJBK25vWkFvSUJBRzdoT1kveWh3UTlEL01HZFJOdEhjT2tKdXFDRFJOWGlVZGpuTE85c3pUWUZBMisKOWgyS2Y0OWdIU0xZUEk4MTA3SDR1L1Z4c3k3eXR3bHpFSmpKL2hzZnJ2WE4xdURoWWV5NlI4LzBNOUxOV29kMgpoNndZWGsrN1dabjN6Z0gvMlRYNm9YL2diQU9aalFXbWlwL3dldTEyZTlxZE1kZEVwS3QyMXZ4L2hUZU42QzVOCmxJeTRmcTJRTzRoeVVsRkxQWmUzcmYrMG5OcUdBVi9IakZGR2hxZTcwK0NRZm8vUlJJODc5YnRsc1AyKzJERVoKOEl5UlN4ZGpIeEQ2Q0oxWWhFUTRVNUVaOHFWYUZwZVB0aVpQNzdkTWlxSStRTFpGNXVjTmZIUEduY084NmR5dQpYWmpSWjlSUmZKVEk1UEt0RGo2endpLzZrUVFURlhSeHF4U1JQMDBDZ2dFQkFMZTF2d3hnRDVzdFIzTUJxZFdQCkJjakdVWWZ2cDY2Ukd3ZWYrVWhhOG5yRHFkVDJVNWJqVkJIWmJFNnlveTNReWQ3TXdiYUtaN2RZejVjdmVHQ3UKV2FBeFdrZTA4THRzS0Z3TXJUdnBBWFF4MFE2eVdDZFlSbklMcmhwUEIyMWViU0w5TlpLZ0Noc1Vrbk1ldHNmWgowTEUvc2FDbmwwcW9RV3BXZFQ1WnNmSlBhaFBOcXdyWDhNQ1lTOU9OUzBTdFoxMTF2bjZtNUF3RlkvbEdMZVl6CkxPLzFsdldNM29rT1JxNXVjR1oxdWZjM1hXS1pTY05tdlFHYUFMK1J2K0ExQnAvOGdpWlhYeHh4bGkrK2FiN0UKL3VnSGJOcXhsVkZZcXo0eHQ2MWtBelZRVUF0Tk9UZHV0R1R4ekM2RkFzZUtCK2lpUHhLYk1xelU5bmk1amI2dQoxMmtDZ2dFQURnK2hiTHFBa3NOZHZuWllUVkFKTEUwSVFSQ0o4VU5aajVqMXdaVjc1WkpmaHhmMTZnbE5yMExLClplZVBrQ1YzL3VPSmFsVVA0ZTQ3K04wQlRWcXJEMU1tRFVHaVp4ZTEvUXRjMDUyTjhzUjJ2cjR6Snp6RUs1NVMKK0lBSUlJaWNhS2F0cXNQMEFFRFJTOEtlb2g5enVISm1OYVNLdU5iYmk0MTlNYktDclNaZmZCaXd2U2Z5ZmQrcApNMDJqMXVKbVB0SmFFZDdRM2VCNGliQWxtNDBUMElRNVQ2bmhWZGtrMzNFS2lORGZBRzk1aEZGZzlWT3dCM2xiCnpMMFNrbFlwSlVrMFcydkV5MTdodU1KVG01YUxpYUFEL1dpNGFkVDE5MzdvVDlzMjFsMHBvL0tabUlSM2hwYUYKVUJ3MDl6aWxWbzBRUzZsb0RkSU5jb3d6RGUyaFVnPT0KLS0tLS1FTkQgUFJJVkFURSBLRVktLS0tLQo=', '9yyREL+9vjYR9vbUGYXC1ypLCI54xKhiXHTKo861BZ0=', NULL),
(2, '', '931fb4c0-d829-48bf-b174-9165bbd0e04b', 1, 'images.jpg', NULL, 'Ricardo Gomes', '03456150504', '1991-02-18', NULL, 'Salvador', 'Bahia', 'Avenida Ulysses Guimarães', 'Sussuarana', NULL, '151', '41213000', '(71) 99990-3997', 'arsgomes.dev@gmail.com', 'c90e1e30511c96607724d3118650491071b81429e3338e9f001b4df9a78d55b3e672e6ddef549fb8968cab9ec819c3363313367165cf0960b3addef8fe987dbc', '1266761ad5303d5ebdefe167848a7324dfef2af8dbba448ec5bd543c5ddf13bbf30712f300287680eb44a3a35cb08e2e1d867ac55b1669a22e634d16b5295edb', '2026-02-05 19:14:42', '2026-02-05 19:14:42', NULL, NULL, NULL, '', '2025-09-22 20:27:23', '2026-02-05 19:14:42', '2025-10-12 20:06:12', '2025-11-11 20:06:12', 1, 0, 1, '', '', '', NULL),
(5, NULL, 'df033623-0857-482e-855b-0de840a72c02', 1, 'images_1.jpg', NULL, 'Ricardo Gomes', '03456150504', '1991-02-18', NULL, 'Salvador', 'BA', 'Avenida Ulysses Guimarães', 'Sussuarana', NULL, '151', '41213000', '(71) 99990-3997', 'arrsgomes.ssa@gmail.com', '67dfcef03c46869ab1d247ed6e94cb2ee8b81ec30f5bd37ff73e2ffb269a37c2a64479a568a83ff928490399039ae51629307b50f47b093afa6990a55fb3d66b', '180b2be32f54f26584074a88d20733de298fb88359f7ade7913d8a0655880cd3d432d205c02309d0199d9e425463917e4ed5a6e075ef35b23db027a1c749fb36', '2026-02-07 18:18:31', '2026-02-07 18:18:31', NULL, NULL, NULL, NULL, '2026-01-23 22:31:36', '2026-02-07 18:18:31', NULL, NULL, 1, 0, 1, NULL, NULL, NULL, NULL),
(6, NULL, 'ec50e8db-67a2-4bc8-ad7d-032d2ef560b2', 1, NULL, NULL, 'ANDRE R R S GOMES', '03456150504', '1991-02-18', NULL, 'Salvador', 'BA', 'Avenida Ulysses Guimarães', 'Sussuarana', NULL, '151', '41213000', '71999903997', 'thetecinfor@gmail.com', '9e335badc1a6edead2af160c9206b22a102df1658ef9ff77d7bee05acf19df5876776e35ced2bb8e0ef0f5c20b830b2721d189a2b391dc2b65b3474baa192580', 'fd48bf3163ed8acb7b31265ca6d0f99dca8f1b2df1d6d9925c51594707083dbba3001235b57c6b333956a6d739bb4e28837529cd967ed65240aeb4f2f067d517', '2026-02-07 20:27:52', '2026-02-07 20:27:52', NULL, NULL, NULL, NULL, '2026-02-05 14:56:41', '2026-02-07 20:27:52', NULL, NULL, 1, 0, 1, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbsys_customer_subscription_customer_payment`
--

CREATE TABLE `tbsys_customer_subscription_customer_payment` (
  `id` bigint(255) NOT NULL,
  `client_subscription_client_id` bigint(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `date_renewal` datetime DEFAULT NULL,
  `date_billing` datetime DEFAULT NULL,
  `date_due` datetime DEFAULT NULL,
  `date_payment` datetime DEFAULT NULL,
  `payment_config_id` int(2) DEFAULT NULL,
  `payment_config_notification_id` bigint(255) DEFAULT NULL,
  `token_notification` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `payment_token` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `card_mask` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `payment_config_brand_id` int(2) DEFAULT NULL,
  `installment` int(2) DEFAULT NULL,
  `error` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `invoice_xml` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `invoice_file` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `nfse` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `nfse_access_key` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Despejando dados para a tabela `tbsys_customer_subscription_customer_payment`
--

INSERT INTO `tbsys_customer_subscription_customer_payment` (`id`, `client_subscription_client_id`, `price`, `date_renewal`, `date_billing`, `date_due`, `date_payment`, `payment_config_id`, `payment_config_notification_id`, `token_notification`, `payment_token`, `card_mask`, `payment_config_brand_id`, `installment`, `error`, `invoice_xml`, `invoice_file`, `nfse`, `nfse_access_key`, `status`) VALUES
(1, 1, 2.99, '2023-04-18 21:39:33', '2023-04-01 21:14:11', '2023-04-30 21:14:11', '2023-04-26 00:14:11', 1, 5, '19b12243-0e43-430b-b0d8-46eddd21c418', NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbsys_customer_usage_counters`
--

CREATE TABLE `tbsys_customer_usage_counters` (
  `id` bigint(255) NOT NULL,
  `customer_id` bigint(255) NOT NULL,
  `month_year` char(7) NOT NULL,
  `tokens_used` int(11) DEFAULT 0,
  `scripts_used` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Despejando dados para a tabela `tbsys_customer_usage_counters`
--

INSERT INTO `tbsys_customer_usage_counters` (`id`, `customer_id`, `month_year`, `tokens_used`, `scripts_used`, `created_at`, `updated_at`) VALUES
(29, 1, '2026-01', 30510, 0, '2026-01-03 22:40:14', '2026-01-06 09:36:46'),
(30, 5, '2026-01', 0, 0, '2026-01-24 23:05:34', NULL),
(31, 1, '2026-02', 0, 0, '2026-02-01 22:59:09', NULL),
(32, 5, '2026-02', 0, 0, '2026-02-05 19:29:25', NULL),
(33, 2, '2026-02', 0, 0, '2026-02-05 19:43:07', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbsys_departments`
--

CREATE TABLE `tbsys_departments` (
  `id` int(5) NOT NULL,
  `user_id_created` bigint(255) NOT NULL,
  `user_id_updated` bigint(255) NOT NULL,
  `title` varchar(30) NOT NULL,
  `description` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Despejando dados para a tabela `tbsys_departments`
--

INSERT INTO `tbsys_departments` (`id`, `user_id_created`, `user_id_updated`, `title`, `description`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Diretoria', 'Setor responsável pela tomada de descisões.', '2024-11-10 22:22:02', '2024-11-28 21:26:44'),
(2, 1, 1, 'Projetos', 'Setor de desenvolvimento de projetos.', '2024-11-10 22:24:19', '2024-11-11 22:21:47'),
(3, 1, 1, 'Técnico', 'Setor responsável pelas instalações.', '2024-11-10 22:26:17', '2024-11-14 12:49:10'),
(4, 1, 1, 'Estagiário', 'Setor referente aos estagiários.', '2024-11-14 12:47:08', '2024-11-27 23:06:47'),
(5, 1, 2, 'Atendimento', 'Setor responsável pelos atendimentos ao cliente.', '2024-11-27 23:14:25', '2025-12-30 14:40:46');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbsys_department_occupations`
--

CREATE TABLE `tbsys_department_occupations` (
  `id` int(15) NOT NULL,
  `department_id` int(10) NOT NULL,
  `user_id_created` bigint(255) DEFAULT NULL,
  `user_id_updated` bigint(255) DEFAULT NULL,
  `title` varchar(30) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Despejando dados para a tabela `tbsys_department_occupations`
--

INSERT INTO `tbsys_department_occupations` (`id`, `department_id`, `user_id_created`, `user_id_updated`, `title`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 0, 'CEO', '2024-11-14 12:35:33', '0000-00-00 00:00:00'),
(2, 2, 1, 1, 'Projetista', '2024-11-14 12:46:20', '2024-11-28 21:26:48'),
(3, 3, 1, 0, 'Gasista', '2024-11-14 12:49:20', '0000-00-00 00:00:00'),
(4, 4, 1, 1, 'teste de ocupação2', '2024-11-27 22:34:03', '2024-11-28 21:05:22'),
(5, 5, 2, 2, 'TESTEOK5', '2024-11-27 22:47:36', '2025-09-12 15:25:32'),
(6, 4, 1, 1, 'Teste de atualização', '2024-11-27 23:06:20', '2024-11-27 23:06:59'),
(7, 5, 1, 2, 'Atendente', '2024-11-27 23:21:53', '2025-08-23 11:20:09'),
(8, 5, 1, 1, 'Lider de Atendimento', '2024-11-27 23:22:34', '2024-11-27 23:25:11'),
(9, 5, 1, 2, 'Atendente Master', '2024-11-27 23:25:18', '2025-12-30 14:40:36'),
(10, 5, 1, 2, 'Atendente Senior', '2024-11-27 23:29:16', '2025-08-23 11:19:59'),
(11, 4, 1, 1, 'teste3', '2024-11-27 23:31:04', '2024-11-27 23:31:42'),
(12, 4, 1, 1, 'teste2', '2024-11-27 23:31:51', '2024-11-27 23:34:02'),
(13, 4, 1, 0, 'teste5', '2024-11-27 23:33:54', '0000-00-00 00:00:00'),
(14, 4, 1, 1, 'teste6', '2024-11-27 23:34:07', '2024-11-27 23:34:15'),
(15, 4, 1, 0, 'teste7', '2024-11-27 23:34:29', '0000-00-00 00:00:00'),
(16, 5, 2, 2, 'Gerente de Atendimento', '2025-08-18 09:15:03', '2025-08-31 18:30:29'),
(17, 5, 2, 2, 'testeOK4', '2025-09-12 15:24:42', '2025-12-30 14:40:42'),
(18, 5, 2, 2, 'TESTE OK007', '2025-09-12 15:25:16', '2025-09-12 15:25:53');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbsys_language`
--

CREATE TABLE `tbsys_language` (
  `id` int(3) NOT NULL,
  `currency_id` int(2) NOT NULL,
  `language` varchar(45) NOT NULL,
  `code` varchar(40) NOT NULL,
  `locale` varchar(10) NOT NULL,
  `archive` varchar(45) NOT NULL,
  `status` int(1) NOT NULL,
  `active` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Despejando dados para a tabela `tbsys_language`
--

INSERT INTO `tbsys_language` (`id`, `currency_id`, `language`, `code`, `locale`, `archive`, `status`, `active`) VALUES
(1, 1, 'Português/Brasil', 'pt_br', 'pt-BR', 'pt_br', 1, 1),
(2, 2, 'Inglês', 'en', 'en-US', 'en', 1, 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbsys_loginattempts`
--

CREATE TABLE `tbsys_loginattempts` (
  `user_id` bigint(255) NOT NULL,
  `time` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbsys_mailer`
--

CREATE TABLE `tbsys_mailer` (
  `id` int(1) NOT NULL,
  `host` text NOT NULL,
  `username` text NOT NULL,
  `passwd` text NOT NULL,
  `port` int(4) NOT NULL,
  `name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Despejando dados para a tabela `tbsys_mailer`
--

INSERT INTO `tbsys_mailer` (`id`, `host`, `username`, `passwd`, `port`, `name`) VALUES
(1, 'smtp.titan.email', 'nao-responder@thetecinfor.com', '^|Fj( h.%J5GSq+', 465, 'Não Responder');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbsys_notification`
--

CREATE TABLE `tbsys_notification` (
  `id` int(3) NOT NULL,
  `title_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `user_id` bigint(255) NOT NULL,
  `type` int(1) NOT NULL,
  `description_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Despejando dados para a tabela `tbsys_notification`
--

INSERT INTO `tbsys_notification` (`id`, `title_type`, `title`, `description`, `updated_at`, `user_id`, `type`, `description_type`, `status`) VALUES
(4, 'Cliente - Formulário de contato - Mensagem Enviada', '', '', '2025-10-23 16:05:37', 2, 5, 'message_message', 1),
(5, 'Cliente - Formulário de contato - Mensagem Respondida', 'RE: {{message.subject}} - {{website.title}}', '<h4>Sua mensagem:</h4>\n<p>{{message.description}}</p>\n<p>enviada pelo formul&aacute;rio de contato.</p>\n<p>{{message.dateReceive}} &agrave;s&nbsp;{{message.hourReceive}}h</p>\n<p>________________________________</p>\n<p>{{message.response}}</p>\n<h5>Mensagem enviada por&nbsp;{{website.username}} no dia&nbsp;{{message.dateSend}} &agrave;s&nbsp;{{message.hourSend}}h.</h5>\n<p>&nbsp;---</p>\n<center>\n<h3 style=\"text-align: left;\">{{website.title}}</h3>\n<p style=\"text-align: left;\">{{website.http}}</p>\n<h3 style=\"text-align: left;\">Mensagem&nbsp;autom&aacute;tica, favor n&atilde;o responder.</h3>\n</center>', '2023-04-24 12:23:04', 1, 5, 'message_response', 1),
(6, 'Cadastro', 'Seu cadastro no sistema {{website.title}} foi realizado com sucesso.', '<h3>Ol&aacute;, {{user.name}}</h3>\n<h3>Seja bem vindo ao sistema {{website.title}}.</h3>\n<p>&nbsp;</p>\n<h4>Senha provis&oacute;ria:&nbsp;</h4>\n<h4>&nbsp;</h4>\n<h1 style=\"text-align: center;\"><span style=\"font-family: \'arial black\', sans-serif; background-color: #ffff00;\"><strong>{{user.password}}</strong></span></h1>\n<h4>&nbsp;</h4>\n<h4 style=\"text-align: center;\">recomendamos a troca ap&oacute;s login, utilizando o menu perfil.</h4>\n<p>&nbsp;</p>\n<h5>Enviado:&nbsp;{{user.date}} &agrave;s&nbsp;{{user.hour}}h</h5>\n<h5>&nbsp;Mensagem enviada por&nbsp;{{website.title}}</h5>\n<h5>{{website.http}}</h5>\n<h5>Mensagem&nbsp;autom&aacute;tica, favor n&atilde;o responder.</h5>', '2025-03-02 14:44:12', 1, 2, 'user_created', 1),
(7, 'Recuperar Senha', 'Sua senha de acesso ao sistema {{website.title}} foi recuperada com sucesso.', '<h3>Ol&aacute;, {{user.name}}</h3>\n<p>&nbsp;</p>\n<h3>Sua senha do sistema {{website.title}} foi recuperada com sucesso.</h3>\n<p>&nbsp;</p>\n<h4>Senha provis&oacute;ria:&nbsp;</h4>\n<h4>&nbsp;</h4>\n<h1 style=\"text-align: center;\"><span style=\"font-family: \'arial black\', sans-serif; background-color: #ffff00;\"><strong>{{user.password}}</strong></span></h1>\n<h4>&nbsp;</h4>\n<h4 style=\"text-align: center;\">recomendamos a troca ap&oacute;s login, utilizando o menu perfil.</h4>\n<p>&nbsp;</p>\n<h5>Enviado:&nbsp;{{user.date}} &agrave;s&nbsp;{{user.hour}}h</h5>\n<h5>&nbsp;Mensagem enviada por&nbsp;{{website.title}}</h5>\n<h5>{{website.http}}</h5>\n<h5>Mensagem&nbsp;autom&aacute;tica, favor n&atilde;o responder.</h5>', '2025-03-09 17:07:39', 1, 2, 'user_recover_password', 1),
(9, 'Conta Bloqueada', 'Sua conta foi bloqueada no sistema {{website.title}}', '<h3>Ol&aacute;, {{user.name}}</h3>\n<h3>&nbsp;</h3>\n<h2 style=\"text-align: center;\">Devido a quantidade de tentativas incorretas de login&nbsp;ao sistema {{website.title}} sua conta foi bloqueada, para desbloquear solicite a um administrador do sistema.</h2>\n<p>&nbsp;&nbsp;</p>\n<h5>Enviado:&nbsp;{{user.date}} &agrave;s&nbsp;{{user.hour}}h</h5>\n<h5>&nbsp;Mensagem enviada por&nbsp;{{website.title}}</h5>\n<h5>{{website.http}}</h5>\n<h5>Mensagem&nbsp;autom&aacute;tica, favor n&atilde;o responder.</h5>', '2025-03-09 22:05:17', 1, 2, 'user_blocked_account', 1),
(25, 'Ticket Criado', 'Seu Ticket foi criado com sucesso: {{ticket.title}} - {{website.title}}', '<h3>Ticket:&nbsp;{{ticket.title}}</h3>\r\n<h4>Descri&ccedil;&atilde;o:&nbsp;{{ticket.description}}</h4>\r\n<h6>Enviado:&nbsp;{{ticket.dateReceive}} &agrave;s&nbsp;{{ticket.hourReceive}}h</h6>\r\n<p>________________________________________</p>\r\n<h5>Seu ticket foi criado com sucesso, gentileza aguardar a resposta da nossa equipe atrav&eacute;s da sua conta cadastrada.&nbsp;</h5>\r\n<p>---</p>\r\n<h6>Mensagem enviada por&nbsp;{{website.title}}</h6>\r\n<h6>{{website.http}}</h6>\r\n<h6>Mensagem&nbsp;autom&aacute;tica, favor n&atilde;o responder.</h6>', '2025-02-16 23:03:39', 1, 6, 'ticket_created', 1),
(26, 'Ticket Respondido', 'Re: {{ticket.title}} - {{website.title}}', '<h2>Ticket:&nbsp;{{ticket.title}}</h2>\n<h3>Descri&ccedil;&atilde;o:&nbsp;{{ticket.description}}</h3>\n<h4>Enviado:&nbsp;{{ticket.dateReceive}} &agrave;s&nbsp;{{ticket.hourReceive}}h</h4>\n<p>________________________________________</p>\n<h3>Seu ticket foi respondido: {{ticket.dateSend}} &agrave;s&nbsp;{{ticket.hourSend}}h</h3>\n<h2>Resposta:&nbsp;{{ticket.response}}</h2>\n<p>---</p>\n<h3>Mensagem enviada por&nbsp;{{website.title}}</h3>\n<p>{{website.http}}</p>\n<p>&nbsp;</p>\n<h3>Mensagem&nbsp;autom&aacute;tica, favor n&atilde;o responder.</h3>', '2023-04-07 22:40:16', 1, 6, 'ticket_response', 1),
(27, 'Ticket Fechado', 'Seu Ticket foi finalizado: {{ticket.title}} - {{website.title}}', '<h3>Ticket:&nbsp;{{ticket.title}}</h3>\n<h4>Descri&ccedil;&atilde;o:&nbsp;{{ticket.description}}</h4>\n<p>________________________________________</p>\n<h5>Seu ticket foi finalizado: {{ticket.dateClosing}} &agrave;s&nbsp;{{ticket.hourClosing}}h</h5>\n<h4>Caso tenha mais alguma d&uacute;vida gentileza abrir um novo ticket.</h4>\n<p>---</p>\n<h6>Mensagem enviada por&nbsp;{{website.title}}</h6>\n<h6>Mensagem enviada: {{ticket.dateSend}} &agrave;s&nbsp;{{ticket.hourSend}}h</h6>\n<h6>{{website.http}}</h6>\n<h6>Mensagem&nbsp;autom&aacute;tica, favor n&atilde;o responder.</h6>', '2025-09-03 12:16:37', 2, 6, 'ticket_closed', 1),
(28, 'Plano de Assinatura Confirmado', 'Uhul! Sua assinatura no {{website.title}} está ativa! 🚀', '<p data-path-to-node=\"5\">Ol&aacute;, <strong data-path-to-node=\"5\" data-index-in-node=\"5\">{{customer.name}}</strong>, que alegria ter voc&ecirc; conosco!</p>\r\n<p data-path-to-node=\"6\">Sua assinatura do plano <strong data-path-to-node=\"6\" data-index-in-node=\"24\">{{plan.title}}</strong>&nbsp;foi confirmada com sucesso. J&aacute; liberamos todos os seus acessos e voc&ecirc; j&aacute; pode come&ccedil;ar a aproveitar as vantagens de ser um membro VIP.</p>\r\n<p data-path-to-node=\"7\"><strong data-path-to-node=\"7\" data-index-in-node=\"0\">Detalhes da sua assinatura:</strong></p>\r\n<ul data-path-to-node=\"8\">\r\n<li>\r\n<p data-path-to-node=\"8,0,0\"><strong data-path-to-node=\"8,0,0\" data-index-in-node=\"0\">Plano:</strong>&nbsp;{{plan.title}} ({{signature.cycle}})</p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"8,1,0\"><strong data-path-to-node=\"8,1,0\" data-index-in-node=\"0\">Data de Renova&ccedil;&atilde;o:</strong>&nbsp;{{signature.date.renewal}}</p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"8,2,0\"><strong data-path-to-node=\"8,2,0\" data-index-in-node=\"0\">Valor:</strong>&nbsp;{{signature.price}}</p>\r\n</li>\r\n</ul>\r\n<p data-path-to-node=\"9\"><strong data-path-to-node=\"9\" data-index-in-node=\"0\">Como come&ccedil;ar agora?</strong> Para acessar sua conta e explorar todas as ferramentas, basta clicar no bot&atilde;o abaixo:</p>\r\n<p data-path-to-node=\"10\"><code data-path-to-node=\"10\" data-index-in-node=\"0\">{{customer.login}}</code></p>\r\n<p data-path-to-node=\"11\">Se precisar de qualquer ajuda ou tiver alguma d&uacute;vida sobre o uso da plataforma, nossa equipe de suporte est&aacute; pronta para te atender. &Eacute; s&oacute; responder a este e-mail!</p>\r\n<p data-path-to-node=\"12\">Seja muito bem-vindo(a) &agrave; nossa comunidade!</p>\r\n<p data-path-to-node=\"13\">Abra&ccedil;os, <strong data-path-to-node=\"13\" data-index-in-node=\"9\">Equipe&nbsp;{{website.title}}</strong></p>', '2026-01-26 17:29:54', 2, 6, 'customer_subscription_confirmed', 1),
(29, 'Plano de Assinatura Cancelado', '{{wesite.title}} - Atualização sobre o cancelamento da sua assinatura 🔄', '<p data-path-to-node=\"5\">Ol&aacute;, <strong data-path-to-node=\"5\" data-index-in-node=\"5\">{{customer.name}}</strong>,</p>\r\n<p data-path-to-node=\"6\">Confirmamos o cancelamento da renova&ccedil;&atilde;o autom&aacute;tica da sua assinatura do plano <strong data-path-to-node=\"6\" data-index-in-node=\"78\">{{plan.title}}</strong>.</p>\r\n<p data-path-to-node=\"7\">Queremos garantir que voc&ecirc; aproveite cada dia do seu investimento. Por isso, seu acesso continua <strong data-path-to-node=\"7\" data-index-in-node=\"101\">totalmente ativo</strong> at&eacute; o final do ciclo atual.</p>\r\n<p data-path-to-node=\"8\"><strong data-path-to-node=\"8\" data-index-in-node=\"0\">O que voc&ecirc; precisa saber:</strong></p>\r\n<ul data-path-to-node=\"9\">\r\n<li>\r\n<p data-path-to-node=\"9,0,0\"><strong data-path-to-node=\"9,0,0\" data-index-in-node=\"0\">Status da Renova&ccedil;&atilde;o:</strong> Desativada. N&atilde;o haver&aacute; novas cobran&ccedil;as.</p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"9,1,0\"><strong data-path-to-node=\"9,1,0\" data-index-in-node=\"0\">Data de T&eacute;rmino do Acesso:</strong>&nbsp;{{signature.renewal}}.</p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"9,2,0\"><strong data-path-to-node=\"9,2,0\" data-index-in-node=\"0\">Uso do Servi&ccedil;o:</strong> Voc&ecirc; pode continuar acessando todas as ferramentas normalmente at&eacute; a data acima.</p>\r\n</li>\r\n</ul>\r\n<p data-path-to-node=\"10\">Sentiremos sua falta! Se o motivo do cancelamento for algo que possamos resolver ou se voc&ecirc; mudar de ideia, saiba que pode reativar sua assinatura a qualquer momento com apenas um clique.</p>\r\n<p data-path-to-node=\"11\">Obrigado por ter estado conosco!</p>\r\n<p data-path-to-node=\"12\">Abra&ccedil;os, <strong data-path-to-node=\"12\" data-index-in-node=\"9\">Equipe&nbsp;{{website.title}}</strong></p>', '2026-01-26 17:36:45', 2, 6, 'customer_subscription_canceled', 1),
(30, 'Plano de Assinatura Renovado', '{{website.title}} - Tudo certo! Sua assinatura foi renovada com sucesso 🚀', '<p data-path-to-node=\"5\">Ol&aacute;, <strong data-path-to-node=\"5\" data-index-in-node=\"5\">{{customer.name}}</strong>,</p>\r\n<p data-path-to-node=\"6\">Temos boas not&iacute;cias! O pagamento da renova&ccedil;&atilde;o da sua assinatura do plano <strong data-path-to-node=\"6\" data-index-in-node=\"73\">{{plan.title}}</strong>&nbsp;foi confirmado.</p>\r\n<p data-path-to-node=\"7\">Seu acesso foi estendido e voc&ecirc; pode continuar utilizando todos os nossos recursos sem nenhuma interrup&ccedil;&atilde;o. &Eacute; &oacute;timo ter voc&ecirc; com a gente por mais um ciclo!</p>\r\n<p data-path-to-node=\"8\"><strong data-path-to-node=\"8\" data-index-in-node=\"0\">Resumo da Renova&ccedil;&atilde;o:</strong></p>\r\n<ul data-path-to-node=\"9\">\r\n<li>\r\n<p data-path-to-node=\"9,0,0\"><strong data-path-to-node=\"9,0,0\" data-index-in-node=\"0\">Plano:</strong>&nbsp;{{plan.title}}</p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"9,1,0\"><strong data-path-to-node=\"9,1,0\" data-index-in-node=\"0\">Novo per&iacute;odo at&eacute;:</strong>&nbsp;{{signature.renewal}}</p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"9,2,0\"><strong data-path-to-node=\"9,2,0\" data-index-in-node=\"0\">Valor:</strong>&nbsp;{{signature.price}}</p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"9,3,0\"><strong data-path-to-node=\"9,3,0\" data-index-in-node=\"0\">Forma de Pagamento:</strong> Cart&atilde;o final [{{signature.card.mask}}]</p>\r\n</li>\r\n</ul>\r\n<p data-path-to-node=\"10\">Voc&ecirc; pode baixar sua nota fiscal ou recibo a qualquer momento acessando o menu <strong data-path-to-node=\"10\" data-index-in-node=\"79\">\"Faturas\"</strong> no seu painel.</p>\r\n<p data-path-to-node=\"11\">Obrigado pela confian&ccedil;a cont&iacute;nua em nosso trabalho!</p>\r\n<p data-path-to-node=\"12\">Abra&ccedil;os, <strong data-path-to-node=\"12\" data-index-in-node=\"9\">Equipe {{website.title}}</strong></p>', '2026-01-26 17:40:26', 2, 6, 'customer_subscription_renewed', 1),
(31, 'Plano de Assinatura Alterado - Upgrade', '{{website.title}} - Parabéns! Seu upgrade para o plano {{new.title}} foi concluído 🚀', '<p data-path-to-node=\"6\">Ol&aacute;, <strong data-path-to-node=\"6\" data-index-in-node=\"5\">{{customer.name}}</strong>, tudo bem?</p>\r\n<p data-path-to-node=\"7\">Vimos que voc&ecirc; decidiu dar um passo adiante e acabamos de confirmar o upgrade da sua assinatura! Agora voc&ecirc; faz parte do plano <strong data-path-to-node=\"7\" data-index-in-node=\"127\">{{plan.title}}</strong>.</p>\r\n<p data-path-to-node=\"8\"><strong data-path-to-node=\"8\" data-index-in-node=\"0\">O que mudou:</strong></p>\r\n<ul data-path-to-node=\"9\">\r\n<li>\r\n<p data-path-to-node=\"9,0,0\"><strong data-path-to-node=\"9,0,0\" data-index-in-node=\"0\">Novo Plano:</strong>&nbsp;{{plan.title}}</p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"9,1,0\"><strong data-path-to-node=\"9,1,0\" data-index-in-node=\"0\">Recursos:</strong> Voc&ecirc; j&aacute; pode acessar todas as novas ferramentas e limites superiores a partir de agora.</p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"9,2,0\"><strong data-path-to-node=\"9,2,0\" data-index-in-node=\"0\">Pr&oacute;xima Renova&ccedil;&atilde;o:</strong>&nbsp;{{signature.renewal}}</p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"9,3,0\"><strong data-path-to-node=\"9,3,0\" data-index-in-node=\"0\">Valor:</strong>&nbsp;{{signature.price}}</p>\r\n</li>\r\n</ul>\r\n<p data-path-to-node=\"10\">Caso o upgrade tenha sido feito no meio do seu ciclo atual, o valor proporcional (pr&oacute;-rata) foi aplicado e o tempo restante do seu plano anterior foi convertido em cr&eacute;dito para este novo plano.</p>\r\n<p data-path-to-node=\"11\">Aproveite ao m&aacute;ximo seu novo n&iacute;vel de acesso!</p>\r\n<p data-path-to-node=\"12\">Abra&ccedil;os, <strong data-path-to-node=\"12\" data-index-in-node=\"9\">Equipe {{website.title}}</strong></p>', '2026-01-26 18:04:59', 2, 6, 'customer_subscription_modification_upgrade', 1),
(32, 'Plano de Assinatura Renovação Automática (Ativado)', '{{website.title}} - Tudo pronto! Sua renovação automática está ativa 💳✅', '<p data-path-to-node=\"5\">Ol&aacute;, <strong data-path-to-node=\"5\" data-index-in-node=\"5\">{{customer.name}}</strong>,</p>\r\n<p data-path-to-node=\"6\">&Oacute;tima not&iacute;cia! Confirmamos que a <strong data-path-to-node=\"6\" data-index-in-node=\"33\">renova&ccedil;&atilde;o autom&aacute;tica</strong> da sua assinatura do plano&nbsp;{{plan.title}}&nbsp;foi ativada com sucesso.</p>\r\n<p data-path-to-node=\"7\"><strong data-path-to-node=\"7\" data-index-in-node=\"0\">O que muda para voc&ecirc;?</strong></p>\r\n<ul data-path-to-node=\"8\">\r\n<li>\r\n<p data-path-to-node=\"8,0,0\"><strong data-path-to-node=\"8,0,0\" data-index-in-node=\"0\">Comodidade:</strong> A partir de agora, voc&ecirc; n&atilde;o precisa mais se preocupar em acessar o painel para realizar pagamentos manuais.</p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"8,1,0\"><strong data-path-to-node=\"8,1,0\" data-index-in-node=\"0\">Acesso Ininterrupto:</strong> Na sua pr&oacute;xima data de renova&ccedil;&atilde;o (<strong data-path-to-node=\"8,1,0\" data-index-in-node=\"55\">{{signature.renewal}}</strong>), nosso sistema processar&aacute; o pagamento automaticamente no cart&atilde;o cadastrado.</p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"8,2,0\"><strong data-path-to-node=\"8,2,0\" data-index-in-node=\"0\">Seguran&ccedil;a:</strong> Voc&ecirc; continuar&aacute; recebendo um e-mail de confirma&ccedil;&atilde;o a cada renova&ccedil;&atilde;o conclu&iacute;da para o seu controle.</p>\r\n</li>\r\n</ul>\r\n<p data-path-to-node=\"9\"><strong data-path-to-node=\"9\" data-index-in-node=\"0\">Dica:</strong> Certifique-se de manter os dados do seu cart&atilde;o atualizados no painel para evitar qualquer interrup&ccedil;&atilde;o sist&ecirc;mica no seu acesso.</p>\r\n<p data-path-to-node=\"10\">Ficamos muito felizes em saber que voc&ecirc; escolheu continuar essa jornada com a gente de forma cont&iacute;nua!</p>\r\n<p data-path-to-node=\"11\">Abra&ccedil;os,</p>\r\n<p data-path-to-node=\"12\"><strong data-path-to-node=\"12\" data-index-in-node=\"0\">Equipe&nbsp;{{website.title}}</strong></p>', '2026-01-26 18:36:42', 2, 6, 'customer_subscription_renewed_automatic', 1),
(33, 'Plano de Assinatura Renovação Automática (Desativado)', '{{website.title}} - A renovação automática da sua assinatura foi desativada 🔔', '<p data-path-to-node=\"5\">Ol&aacute;, <strong data-path-to-node=\"5\" data-index-in-node=\"5\">{{custome.name}}</strong>,</p>\r\n<p data-path-to-node=\"6\">Conforme sua solicita&ccedil;&atilde;o, a <strong data-path-to-node=\"6\" data-index-in-node=\"28\">renova&ccedil;&atilde;o autom&aacute;tica</strong> da sua assinatura do plano <strong data-path-to-node=\"6\" data-index-in-node=\"76\">{{plan.title}}</strong>&nbsp;foi desativada com sucesso.</p>\r\n<p data-path-to-node=\"7\"><strong data-path-to-node=\"7\" data-index-in-node=\"0\">O que acontece agora?</strong></p>\r\n<ul data-path-to-node=\"8\">\r\n<li>\r\n<p data-path-to-node=\"8,0,0\"><strong data-path-to-node=\"8,0,0\" data-index-in-node=\"0\">Acesso atual:</strong> Voc&ecirc; continuar&aacute; com acesso total a todos os recursos at&eacute; o dia <strong data-path-to-node=\"8,0,0\" data-index-in-node=\"77\">{{signature.renewal}}</strong>.</p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"8,1,0\"><strong data-path-to-node=\"8,1,0\" data-index-in-node=\"0\">Pr&oacute;xima cobran&ccedil;a:</strong> N&atilde;o faremos nenhuma nova cobran&ccedil;a autom&aacute;tica no seu cart&atilde;o.</p>\r\n</li>\r\n</ul>\r\n<p data-path-to-node=\"9\">⚠️ <strong data-path-to-node=\"9\" data-index-in-node=\"3\">Aten&ccedil;&atilde;o:</strong> Como a renova&ccedil;&atilde;o autom&aacute;tica est&aacute; desligada, para continuar utilizando nossos servi&ccedil;os ap&oacute;s o dia <strong data-path-to-node=\"9\" data-index-in-node=\"109\">{{signature.renewal}}</strong>, <strong data-path-to-node=\"9\" data-index-in-node=\"130\">voc&ecirc; precisar&aacute; realizar o pagamento manualmente</strong>.</p>\r\n<p data-path-to-node=\"10\">Basta acessar o seu painel, ir at&eacute; o menu <strong data-path-to-node=\"10\" data-index-in-node=\"42\">\"Faturas\"</strong> e efetuar o pagamento da nova fatura que ser&aacute; gerada na data de vencimento.</p>\r\n<p data-path-to-node=\"11\">Se voc&ecirc; decidir que quer voltar ao sistema autom&aacute;tico e n&atilde;o se preocupar com datas, basta reativar a renova&ccedil;&atilde;o no seu painel a qualquer momento.</p>\r\n<p data-path-to-node=\"12\">Atenciosamente,</p>\r\n<p data-path-to-node=\"13\"><strong data-path-to-node=\"13\" data-index-in-node=\"0\">Equipe&nbsp;{{website.title}}</strong></p>', '2026-01-26 18:31:04', 2, 6, 'customer_subscription_renewed_disabled', 1),
(34, 'Plano de Assinatura Aviso de Cobrança', '', '', '2025-02-10 00:01:03', 1, 6, 'customer_subscription_billing_notice', 1),
(35, 'Plano de Assinatura Aviso de Cancelamento', '', '', '2025-02-10 00:00:54', 1, 6, 'customer_subscription_canceled_notice', 1),
(36, 'Plano de Assinatura Cancelamento Automático', '{{website.title}} - Seu acesso ao plano [Nome do Plano] chegou ao fim 🛑', '<p data-path-to-node=\"5\">Ol&aacute;, <strong data-path-to-node=\"5\" data-index-in-node=\"5\">{{customer.name}}</strong>,</p>\r\n<p data-path-to-node=\"6\">Estamos passando para informar que o per&iacute;odo da sua assinatura do plano <strong data-path-to-node=\"6\" data-index-in-node=\"72\">{{plan.title}}</strong>&nbsp;encerrou hoje e as funcionalidades exclusivas foram desativadas na sua conta.</p>\r\n<p data-path-to-node=\"7\"><strong data-path-to-node=\"7\" data-index-in-node=\"0\">O que mudou na sua conta:</strong></p>\r\n<ul data-path-to-node=\"8\">\r\n<li>\r\n<p data-path-to-node=\"8,0,0\"><strong data-path-to-node=\"8,0,0\" data-index-in-node=\"0\">Acesso:</strong> Suas ferramentas e recursos n&atilde;o est&atilde;o mais dispon&iacute;veis.</p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"8,2,0\"><strong data-path-to-node=\"8,2,0\" data-index-in-node=\"0\">Hist&oacute;rico:</strong> Suas faturas anteriores continuam dispon&iacute;veis para consulta no seu painel.</p>\r\n</li>\r\n</ul>\r\n<p data-path-to-node=\"9\"><strong data-path-to-node=\"9\" data-index-in-node=\"0\">Sentiremos sua falta!</strong> Sabemos que imprevistos acontecem ou que planos mudam. Se voc&ecirc; apenas esqueceu de renovar ou deseja voltar agora mesmo, basta clicar no bot&atilde;o abaixo para reativar seu acesso instantaneamente:</p>\r\n<p data-path-to-node=\"10\"><code data-path-to-node=\"10\" data-index-in-node=\"0\">{{btn.new.plan}}</code></p>\r\n<p data-path-to-node=\"11\">Ficamos muito gratos pelo tempo que passamos juntos e esperamos te ver de volta em breve!</p>\r\n<p data-path-to-node=\"12\">Atenciosamente,</p>\r\n<p data-path-to-node=\"13\"><strong data-path-to-node=\"13\" data-index-in-node=\"0\">Equipe {{website.title}}</strong></p>', '2026-01-26 18:40:02', 2, 6, 'customer_subscription_canceled_automatic_notice', 1),
(37, 'Conta Bloqueada', 'Sua conta foi bloqueada no sistema {{website.title}}', '<h3>Ol&aacute;, {{customer.name}}</h3>\r\n<h3>&nbsp;</h3>\r\n<h2 style=\"text-align: center;\">Devido a quantidade de tentativas incorretas de login&nbsp;ao sistema {{website.title}} sua conta foi bloqueada, para desbloquear solicite a um administrador do sistema.</h2>\r\n<p>&nbsp;&nbsp;</p>\r\n<h5>Enviado:&nbsp;{{customer.date}} &agrave;s&nbsp;{{customer.hour}}h</h5>\r\n<h5>&nbsp;Mensagem enviada por&nbsp;{{website.title}}</h5>\r\n<h5>{{website.http}}</h5>\r\n<h5>Mensagem&nbsp;autom&aacute;tica, favor n&atilde;o responder.</h5>', '2025-12-30 15:57:06', 2, 6, 'sys_blocked_account', 1),
(38, 'Cadastro', 'Bem-vindo(a) ao {{website.title}}!', '<p class=\"isSelectedEnd\">Ol&aacute;, {{customer.name}}!</p>\r\n<p class=\"isSelectedEnd\">Seja muito bem-vindo(a) ao <strong>{{website.title}}</strong>.<br />Seu cadastro foi realizado com sucesso e agora voc&ecirc; j&aacute; pode aproveitar todos os recursos dispon&iacute;veis em nosso sistema.</p>\r\n<p class=\"isSelectedEnd\">Caso tenha qualquer d&uacute;vida ou precise de ajuda, acesse nosso site para mais informa&ccedil;&otilde;es.</p>\r\n<p class=\"isSelectedEnd\"><strong>Data do cadastro:</strong> {{customer.date}} &agrave;s {{customer.hour}}h</p>\r\n<p class=\"isSelectedEnd\">Atenciosamente,<br />Equipe <strong>{{website.title}}</strong><br />{{website.http}}</p>\r\n<div contenteditable=\"false\"><hr /></div>\r\n<p>Esta &eacute; uma mensagem autom&aacute;tica.<br />Por favor, n&atilde;o responda a este e-mail.</p>', '2026-01-23 15:17:58', 2, 6, 'customer_created', 1),
(39, 'Recuperar Senha', 'Sua senha de acesso ao sistema {{website.title}} foi recuperada com sucesso.', '<h3>Ol&aacute;, {{customer.name}}</h3>\r\n<p>&nbsp;</p>\r\n<h3>Sua senha do sistema {{website.title}} foi recuperada com sucesso.</h3>\r\n<p>&nbsp;</p>\r\n<h4>Senha provis&oacute;ria:&nbsp;</h4>\r\n<h4>&nbsp;</h4>\r\n<h1 style=\"text-align: center;\"><span style=\"background-color: #ffff00;\"><span style=\"font-family: \'arial black\', sans-serif;\">{{</span>customer<span style=\"font-family: \'arial black\', sans-serif;\">.pas</span></span><span style=\"font-family: \'arial black\', sans-serif; background-color: #ffff00;\"><strong>sword}}</strong></span></h1>\r\n<h4>&nbsp;</h4>\r\n<h4 style=\"text-align: center;\">recomendamos a troca ap&oacute;s login, utilizando o menu perfil.</h4>\r\n<p>&nbsp;</p>\n<h5>Enviado:&nbsp;{{customer.date}} &agrave;s&nbsp;{{customer.hour}}h</h5>\n<h5>&nbsp;Mensagem enviada por&nbsp;{{website.title}}</h5>\n<h5>{{website.http}}</h5>\n<h5>Mensagem&nbsp;autom&aacute;tica, favor n&atilde;o responder.</h5>', '2025-08-19 15:41:16', 2, 6, 'customer_recover_password', 1),
(40, 'Excluir Conta', '', '', '2025-08-19 15:41:45', 1, 6, 'customer_delete_account', 1),
(41, 'Ticket Reaberto', 'Seu Ticket foi reaberto: {{ticket.title}} - {{website.title}}', '<h3>Ticket:&nbsp;{{ticket.title}}</h3>\r\n<h4>Descri&ccedil;&atilde;o:&nbsp;{{ticket.description}}</h4>\r\n<p>________________________________________</p>\r\n<h5>Seu ticket foi&nbsp;reaberto: {{ticket.dateReopening}} &agrave;s&nbsp;{{ticket.hourReopening}}h</h5>\r\n<h4><strong>Voc&ecirc; pode voltar a nos enviar mensagens por meio do seu ticket.</strong></h4>\r\n<p>&nbsp;</p>\r\n<p>---</p>\r\n<h6>Mensagem enviada por&nbsp;{{website.title}}</h6>\r\n<h6>Mensagem enviada: {{ticket.dateSend}} &agrave;s&nbsp;{{ticket.hourSend}}h</h6>\r\n<h6>{{website.http}}</h6>\r\n<h6>Mensagem&nbsp;autom&aacute;tica, favor n&atilde;o responder.</h6>', '2025-09-13 10:38:20', 2, 6, 'ticket_reopen', 1),
(42, 'Plano de Assinatura NFS-e', 'Disponível: NFS-e referente ao plano contratado -  {{website.title}}', '<h3>Ol&aacute;, {{customer.name}}</h3>\r\n<p>&nbsp;</p>\r\n<h3>Segue anexa a Nota Fiscal de Servi&ccedil;o, referente ao pagamento do plano de assinatura adquirido</h3>\r\n<h3>&nbsp;</h3>\r\n<h3>Plano: {{signature.plan}}</h3>\r\n<h3>Data do Faturamento: {{signature.dateBilling}}</h3>\r\n<h3>Data do Pagamento: {{signature.datePayment}}</h3>\r\n<h3>Valor do Plano: {{signature.pricePlan}}</h3>\r\n<h3>Desconto: {{signature.discount}}</h3>\r\n<h3>Valor com Desconto: {{signature.netAmount}}</h3>\r\n<h3>&nbsp;</h3>\r\n<h3>URL para consulta: {{signature.url}}</h3>\r\n<p>&nbsp;</p>\r\n<h5>Enviado:&nbsp;{{customer.date}} &agrave;s&nbsp;{{customer.hour}}h</h5>\r\n<h5>&nbsp;Mensagem enviada por&nbsp;{{website.title}}</h5>\r\n<h5>{{website.http}}</h5>\r\n<h5>Mensagem&nbsp;autom&aacute;tica, favor n&atilde;o responder.</h5>', '2025-09-11 21:29:59', 2, 6, 'client_subscription_nfse', 1),
(43, 'Plano de Assinatura Cancelamento 7 Dias', '{{website.title}} - Confirmação de cancelamento e estorno da sua assinatura 💳', '<p data-path-to-node=\"5\">Ol&aacute;, <strong data-path-to-node=\"5\" data-index-in-node=\"5\">{{customer.name}}</strong>, tudo bem?</p>\r\n<p data-path-to-node=\"6\">Recebemos sua solicita&ccedil;&atilde;o de cancelamento da assinatura <strong>{{customer.plan.title}}</strong>. Como voc&ecirc; est&aacute; dentro do prazo de 7 dias garantido pelo C&oacute;digo de Defesa do Consumidor, o seu estorno j&aacute; foi processado automaticamente em nosso sistema.</p>\r\n<p data-path-to-node=\"7\"><strong data-path-to-node=\"7\" data-index-in-node=\"0\">Informa&ccedil;&otilde;es importantes sobre o seu reembolso:</strong></p>\r\n<ul data-path-to-node=\"8\">\r\n<li>\r\n<p data-path-to-node=\"8,0,0\"><strong data-path-to-node=\"8,0,0\" data-index-in-node=\"0\">Valor:</strong> O valor integral foi devolvido ao cart&atilde;o utilizado na compra.</p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"8,1,0\"><strong data-path-to-node=\"8,1,0\" data-index-in-node=\"0\">Prazo:</strong> O cr&eacute;dito poder&aacute; aparecer na sua fatura atual ou na pr&oacute;xima, dependendo da data de fechamento e das pol&iacute;ticas da sua operadora de cart&atilde;o.</p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"8,2,0\"><strong data-path-to-node=\"8,2,0\" data-index-in-node=\"0\">Comprovante:</strong> O ID da transa&ccedil;&atilde;o de estorno junto &agrave; Ef&iacute;Pay &eacute;: <code data-path-to-node=\"8,2,0\" data-index-in-node=\"60\"># <strong>{{customer_id_charge}}</strong></code>.</p>\r\n</li>\r\n</ul>\r\n<p data-path-to-node=\"9\">Lamentamos que n&atilde;o tenha seguido conosco desta vez, mas as portas estar&atilde;o sempre abertas caso decida voltar no futuro!</p>\r\n<p data-path-to-node=\"10\">Se tiver qualquer d&uacute;vida, entre em contato com o suporte.</p>\r\n<p data-path-to-node=\"11\">Abra&ccedil;os, <strong data-path-to-node=\"11\" data-index-in-node=\"9\">Equipe {{website.title}}</strong></p>', '2026-01-26 18:23:47', 2, 6, 'customer_subscription_canceled_7_notice', 1),
(44, 'Plano de Assinatura Alterado - Downgrade', '{{website.title}} - Alteração de assinatura: Mudança para o plano {{plan.title}} 🔄', '<p data-path-to-node=\"17\">Ol&aacute;, <strong data-path-to-node=\"17\" data-index-in-node=\"5\">{{customer.name}}</strong>,</p>\r\n<p data-path-to-node=\"18\">Confirmamos a sua solicita&ccedil;&atilde;o de mudan&ccedil;a para o plano <strong data-path-to-node=\"18\" data-index-in-node=\"54\">{{plan.title}}</strong>.</p>\r\n<p data-path-to-node=\"19\"><strong data-path-to-node=\"19\" data-index-in-node=\"0\">Importante:</strong> Como voc&ecirc; j&aacute; tinha pago pelo plano atual, voc&ecirc; continuar&aacute; com acesso aos recursos do plano <strong data-path-to-node=\"19\" data-index-in-node=\"103\">{{plan.previous.title</strong><strong data-path-to-node=\"19\" data-index-in-node=\"103\">}}</strong>&nbsp;at&eacute; o dia <strong data-path-to-node=\"19\" data-index-in-node=\"136\">{{plan.previous.date}}</strong>.</p>\r\n<p data-path-to-node=\"20\">Ap&oacute;s essa data, sua assinatura ser&aacute; renovada automaticamente com o valor reduzido de <strong data-path-to-node=\"20\" data-index-in-node=\"85\">{{plan.price}}</strong>&nbsp;e os limites do novo plano ser&atilde;o aplicados.</p>\r\n<p data-path-to-node=\"20\">&nbsp;</p>\r\n<p data-path-to-node=\"21\"><strong data-path-to-node=\"21\" data-index-in-node=\"0\">Resumo da altera&ccedil;&atilde;o:</strong></p>\r\n<ul data-path-to-node=\"22\">\r\n<li>\r\n<p data-path-to-node=\"22,0,0\"><strong data-path-to-node=\"22,0,0\" data-index-in-node=\"0\">Plano Atual (at&eacute; [Data]):</strong>&nbsp;{{plan.previous.title}}</p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"22,1,0\"><strong data-path-to-node=\"22,1,0\" data-index-in-node=\"0\">Novo Plano (p&oacute;s [Data]):</strong>&nbsp;{{plan.title}}</p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"22,2,0\"><strong data-path-to-node=\"22,2,0\" data-index-in-node=\"0\">Valor da Nova Renova&ccedil;&atilde;o:</strong>&nbsp;{{plan.price}}</p>\r\n</li>\r\n</ul>\r\n<p data-path-to-node=\"23\">Se precisar de qualquer ajuda ou decidir voltar para o plano anterior antes da virada do ciclo, basta acessar seu painel!</p>\r\n<p data-path-to-node=\"24\">Atenciosamente, <strong data-path-to-node=\"24\" data-index-in-node=\"16\">Equipe&nbsp;{{website.title}}</strong></p>', '2026-01-26 18:12:20', 2, 6, 'customer_subscription_modification_downgrade', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbsys_notification_type`
--

CREATE TABLE `tbsys_notification_type` (
  `id` int(2) NOT NULL,
  `title` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `type` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbsys_payment_config`
--

CREATE TABLE `tbsys_payment_config` (
  `id` int(2) NOT NULL,
  `title` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `user_id_updated` bigint(255) DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Despejando dados para a tabela `tbsys_payment_config`
--

INSERT INTO `tbsys_payment_config` (`id`, `title`, `updated_at`, `user_id_updated`, `status`) VALUES
(1, 'Efi Pay', '2023-05-02 22:28:00', 1, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbsys_payment_config_brand`
--

CREATE TABLE `tbsys_payment_config_brand` (
  `id` int(2) NOT NULL,
  `payment_config_id` int(2) NOT NULL,
  `title` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `description` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `image` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Despejando dados para a tabela `tbsys_payment_config_brand`
--

INSERT INTO `tbsys_payment_config_brand` (`id`, `payment_config_id`, `title`, `description`, `image`, `status`) VALUES
(1, 1, 'VISA', 'visa', 'visa.png', 1),
(2, 1, 'MasterCard', 'mastercard', 'mastercard.png', 1),
(3, 1, 'America Express', 'amex', 'amex.png', 1),
(4, 1, 'ELO', 'elo', 'elo.png', 1),
(5, 1, 'Hipercard', 'hipercard', 'hipercard.png', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbsys_payment_methods`
--

CREATE TABLE `tbsys_payment_methods` (
  `id` int(2) NOT NULL,
  `title` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `payment_config_id` int(2) DEFAULT NULL,
  `payment_method` varchar(20) DEFAULT NULL,
  `status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Despejando dados para a tabela `tbsys_payment_methods`
--

INSERT INTO `tbsys_payment_methods` (`id`, `title`, `payment_config_id`, `payment_method`, `status`) VALUES
(1, 'Cartão de Crédito', 1, 'credit_card', 1),
(2, 'Cartão de Débito', 1, NULL, 1),
(3, 'PIX', 1, NULL, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbsys_payment_status`
--

CREATE TABLE `tbsys_payment_status` (
  `id` bigint(255) NOT NULL,
  `payment_config_id` int(2) NOT NULL,
  `title` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `description` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `status` int(1) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `user_id_created` bigint(255) DEFAULT NULL,
  `user_id_updated` bigint(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Despejando dados para a tabela `tbsys_payment_status`
--

INSERT INTO `tbsys_payment_status` (`id`, `payment_config_id`, `title`, `description`, `status`, `created_at`, `updated_at`, `user_id_created`, `user_id_updated`) VALUES
(1, 1, 'Novo', 'new', 3, NULL, NULL, NULL, NULL),
(2, 1, 'Aguardando', 'waiting', 3, NULL, NULL, NULL, NULL),
(3, 1, 'Identificado', 'identified', 3, NULL, NULL, NULL, NULL),
(4, 1, 'Pago', 'paid', 1, NULL, NULL, NULL, NULL),
(5, 1, 'Não Pago', 'unpaid', 2, NULL, NULL, NULL, NULL),
(6, 1, 'Devolvido', 'refunded', 2, NULL, NULL, NULL, NULL),
(7, 1, 'Contestado', 'contested', 2, NULL, NULL, NULL, NULL),
(8, 1, 'Cancelado', 'canceled', 2, NULL, NULL, NULL, NULL),
(9, 1, 'Marcar como pago manualmente', 'settled', 1, NULL, NULL, NULL, NULL),
(10, 1, 'Via Link', 'link', 2, NULL, NULL, NULL, NULL),
(11, 1, 'Expirado', 'expired', 2, NULL, NULL, NULL, NULL),
(12, 1, 'Assinatura Ativa', 'active', 2, NULL, NULL, NULL, NULL),
(13, 1, 'Assinatura Criada', 'new_charge', 2, NULL, NULL, NULL, NULL),
(14, 1, 'Aprovado', 'approved', 3, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbsys_privilege`
--

CREATE TABLE `tbsys_privilege` (
  `id` int(2) NOT NULL,
  `description` varchar(20) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `user_id_created` bigint(255) DEFAULT NULL,
  `user_id_updated` bigint(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Despejando dados para a tabela `tbsys_privilege`
--

INSERT INTO `tbsys_privilege` (`id`, `description`, `created_at`, `updated_at`, `user_id_created`, `user_id_updated`) VALUES
(1, 'Administrador', '2025-08-01 10:50:37', NULL, 1, 0),
(2, 'Atendentes', '2025-08-01 10:50:42', '2025-08-16 17:28:33', 1, 2),
(3, 'Padrão', '2025-08-16 11:20:10', NULL, 2, NULL),
(4, 'Desenvolvedor', '2025-08-31 17:48:45', '2025-08-31 18:10:49', 2, 2);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbsys_privilege_type`
--

CREATE TABLE `tbsys_privilege_type` (
  `id` int(4) NOT NULL,
  `description` varchar(40) NOT NULL,
  `description_type` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Despejando dados para a tabela `tbsys_privilege_type`
--

INSERT INTO `tbsys_privilege_type` (`id`, `description`, `description_type`) VALUES
(1, 'Configurações - Gerais', 'configuration'),
(2, 'Configurações - Email', 'smtp_configuration'),
(3, 'Usuários - Visualizar', 'user_view'),
(4, 'Usuários - Editar', 'user_edit'),
(5, 'Usuários - Deletar', 'user_delete'),
(6, 'Usuários - Cadastrar', 'user_create'),
(7, 'Departamentos - Visualizar', 'department_view'),
(8, 'Departamentos - Editar', 'department_edit'),
(9, 'Departamentos - Deletar', 'department_delete'),
(10, 'Departamentos - Cadastrar', 'department_create'),
(17, 'Notificações - Visualizar', 'notification_view'),
(18, 'Notificações - Editar', 'notification_edit'),
(29, 'Configurações - Privilégios', 'privileges_configuration'),
(37, 'Configurações - Empresa', 'configuration_company'),
(67, 'Youtube - Listar', 'yt_optimization_list'),
(68, 'Clientes - Assinaturas', 'customer_signatures'),
(69, 'Clientes - Visualizar', 'customer_view'),
(70, 'Clientes - Editar', 'customer_edit'),
(71, 'Tickets (Departamento) - Visualizar', 'ticket_department_view'),
(72, 'Tickets (Atendentes) - Cadastrar', 'ticket_agents_create'),
(73, 'Tickets (Atendentes) - Visualizar', 'ticket_agents_view'),
(74, 'Tickets - Responder', 'ticket_send'),
(75, 'Tickets - Visualizar', 'ticket_view'),
(76, 'Tickets - Encerrar', 'ticket_close'),
(77, 'Tickets - Excluir', 'ticket_delete'),
(78, 'Tickets - Desativar Mensagem', 'ticket_send_deactivate'),
(79, 'Tickets - Excluir Mensagem', 'ticket_send_delete'),
(80, 'Planos de Acesso - Visualizar', 'access_plans_view'),
(81, 'Planos de Acesso - Editar', 'access_plans_edit'),
(82, 'Planos de Acesso - Cadastrar', 'access_plans_create'),
(83, 'Cupons de Desconto - Visualizar', 'access_plans_coupons_view'),
(84, 'Cupons de Desconto - Cadastrar', 'access_plans_coupons_create'),
(85, 'Cupons de Desconto - Editar', 'access_plans_coupons_edit'),
(86, 'Tickets - Reabrir', 'ticket_reopen'),
(87, 'Tickets (Departamento) - Cadastrar', 'ticket_department_create');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbsys_privilege_type_privilege`
--

CREATE TABLE `tbsys_privilege_type_privilege` (
  `id` bigint(255) NOT NULL,
  `privilege_id` int(2) NOT NULL,
  `privilege_type_id` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Despejando dados para a tabela `tbsys_privilege_type_privilege`
--

INSERT INTO `tbsys_privilege_type_privilege` (`id`, `privilege_id`, `privilege_type_id`) VALUES
(64, 1, 70),
(65, 1, 68),
(66, 1, 69),
(67, 1, 2),
(68, 1, 1),
(69, 1, 37),
(70, 1, 29),
(71, 1, 10),
(72, 1, 9),
(73, 1, 8),
(74, 1, 7),
(75, 1, 18),
(76, 1, 17),
(77, 1, 82),
(78, 1, 81),
(79, 1, 80),
(80, 1, 84),
(81, 1, 85),
(82, 1, 83),
(83, 1, 72),
(84, 1, 87),
(85, 1, 78),
(86, 1, 76),
(87, 1, 77),
(88, 1, 79),
(89, 1, 86),
(90, 1, 74),
(91, 1, 75),
(92, 1, 73),
(93, 1, 71),
(94, 1, 6),
(95, 1, 5),
(96, 1, 4),
(97, 1, 3),
(98, 1, 67);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbsys_signatures`
--

CREATE TABLE `tbsys_signatures` (
  `id` bigint(255) NOT NULL,
  `gcid` text NOT NULL,
  `customer_id` bigint(255) NOT NULL,
  `access_plan_id` bigint(255) NOT NULL,
  `currency_id` int(2) DEFAULT NULL,
  `price` double(10,2) NOT NULL,
  `discount` double(10,2) DEFAULT NULL,
  `date_start` datetime NOT NULL,
  `date_end` datetime DEFAULT NULL,
  `auto_renew` int(1) NOT NULL DEFAULT 0,
  `auto_renew_accepted_at` datetime DEFAULT NULL,
  `date_renovation` datetime DEFAULT NULL,
  `renewal_cycle` varchar(10) DEFAULT NULL,
  `access_plan_coupon_id` bigint(255) DEFAULT NULL,
  `status` int(1) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `user_id_updated` bigint(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Despejando dados para a tabela `tbsys_signatures`
--

INSERT INTO `tbsys_signatures` (`id`, `gcid`, `customer_id`, `access_plan_id`, `currency_id`, `price`, `discount`, `date_start`, `date_end`, `auto_renew`, `auto_renew_accepted_at`, `date_renovation`, `renewal_cycle`, `access_plan_coupon_id`, `status`, `created_at`, `updated_at`, `user_id_updated`) VALUES
(1, 'e7982809-c97e-4a8c-87c8-e2c83b33c9a4', 1, 1, 1, 590.00, 10.00, '2025-04-18 16:40:39', NULL, 1, '2026-02-08 18:04:27', '2027-02-08 18:04:25', 'anual', 3, 1, '2025-09-21 23:57:12', '2026-02-08 18:04:27', NULL),
(27, 'ebb9d439-c7e4-41f2-82ef-1b893b1f323e', 5, 3, 1, 990.00, 10.00, '2025-02-05 19:13:18', NULL, 1, '2026-02-07 18:10:46', '2027-02-07 18:10:45', 'anual', 3, 1, '2026-02-05 19:13:18', '2026-02-07 18:10:46', NULL),
(29, 'fc75b621-9e0b-490d-a762-b9496b4b5039', 2, 3, 1, 99.90, NULL, '2026-02-05 19:27:55', NULL, 0, NULL, '2026-03-08 19:27:55', 'mensal', NULL, 1, '2026-02-05 19:27:55', '2026-02-05 19:27:56', NULL),
(33, '20830c9a-545a-4822-8cc0-104faa309061', 6, 1, 1, 599.00, NULL, '2026-02-07 20:42:34', NULL, 1, '2026-02-07 20:42:34', '2027-02-07 20:42:34', 'anual', NULL, 1, '2026-02-07 20:42:34', NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbsys_signatures_auto_renew_history`
--

CREATE TABLE `tbsys_signatures_auto_renew_history` (
  `id` bigint(255) NOT NULL,
  `signature_id` bigint(255) NOT NULL,
  `term_version` varchar(20) NOT NULL,
  `term_hash` char(64) NOT NULL,
  `term_title` text NOT NULL,
  `term_text` text NOT NULL,
  `accepted_at` datetime NOT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `user_agent` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `accepted_by` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT 'customer',
  `source` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT 'web',
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `tbsys_signatures_auto_renew_history`
--

INSERT INTO `tbsys_signatures_auto_renew_history` (`id`, `signature_id`, `term_version`, `term_hash`, `term_title`, `term_text`, `accepted_at`, `ip_address`, `user_agent`, `accepted_by`, `source`, `created_at`) VALUES
(18, 1, 'v1.0', 'd4691b5b05793339745b964034e53a732c491052b2b8ad32a038cfd607424b6e', 'Termos de Renovação Automática', 'Ao contratar e utilizar os serviços disponibilizados pela plataforma, o CLIENTE declara que leu, compreendeu e concorda expressamente com os termos abaixo relacionados à renovação automática da assinatura, nos termos da legislação brasileira vigente, especialmente o Código de Defesa do Consumidor (Lei nº 8.078/1990).\r\n\r\n1. Da Renovação Automática\r\n\r\n1.1. A assinatura do serviço contratado possui renovação automática ao final de cada período contratado, pelo mesmo prazo e nas mesmas condições vigentes no momento da renovação, salvo comunicação em contrário pelo CLIENTE.\r\n\r\n1.2. A renovação automática tem como objetivo garantir a continuidade do acesso ao serviço, evitando interrupções não desejadas.\r\n\r\n2. Da Informação Clara e Transparente\r\n\r\n2.1. O CLIENTE declara estar ciente de forma prévia, clara, precisa e ostensiva sobre:\r\n\r\nO valor da assinatura;\r\n\r\nO período de vigência;\r\n\r\nA forma de cobrança;\r\n\r\nA existência da renovação automática.\r\n\r\n2.2. Qualquer alteração relevante de preço ou condições será informada previamente ao CLIENTE, respeitando a legislação aplicável.\r\n\r\n3. Do Consentimento Expresso\r\n\r\n3.1. A renovação automática não configura serviço não solicitado, uma vez que o CLIENTE manifesta seu consentimento expresso ao aceitar este termo no momento da contratação.\r\n\r\n3.2. Este consentimento poderá ser revogado a qualquer momento, conforme disposto neste termo.\r\n\r\n4. Do Direito de Cancelamento\r\n\r\n4.1. O CLIENTE poderá desativar a renovação automática ou cancelar a assinatura a qualquer tempo, de forma simples, imediata e sem a imposição de obstáculos indevidos, por meio da área administrativa da plataforma ou outro canal disponibilizado.\r\n\r\n4.2. O cancelamento da renovação automática não gera penalidades, taxas adicionais ou ônus desproporcionais ao CLIENTE, respeitando o disposto nos artigos 39 e 51 do Código de Defesa do Consumidor.\r\n\r\n4.3. Após o cancelamento, o CLIENTE manterá o acesso ao serviço até o término do período já pago, não sendo realizadas novas cobranças.\r\n\r\n5. Da Fidelidade\r\n\r\n5.1. Caso exista período de fidelidade, o CLIENTE será informado de forma destacada.\r\n\r\n5.2. A renovação automática de eventual período de fidelidade exigirá novo consentimento expresso do CLIENTE, não sendo presumida em nenhuma hipótese.\r\n\r\n6. Da Conformidade Legal\r\n\r\n6.1. Este termo está em conformidade com o Código de Defesa do Consumidor, não contendo cláusulas abusivas ou que coloquem o CLIENTE em desvantagem exagerada.\r\n\r\n6.2. Em caso de dúvidas, o CLIENTE poderá buscar orientação junto a órgãos de defesa do consumidor, como o PROCON ou o IDEC, sem prejuízo do direito de acesso ao Poder Judiciário.\r\n\r\n7. Do Aceite\r\n\r\n7.1. Ao prosseguir com a contratação e/ou utilização do serviço, o CLIENTE declara que leu, compreendeu e concorda integralmente com este Termo de Consentimento para Renovação Automática.', '2026-02-05 17:19:11', '45.171.13.34', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'customer', 'web', NULL),
(19, 27, 'v1.0', 'd4691b5b05793339745b964034e53a732c491052b2b8ad32a038cfd607424b6e', 'Termos de Renovação Automática', 'Ao contratar e utilizar os serviços disponibilizados pela plataforma, o CLIENTE declara que leu, compreendeu e concorda expressamente com os termos abaixo relacionados à renovação automática da assinatura, nos termos da legislação brasileira vigente, especialmente o Código de Defesa do Consumidor (Lei nº 8.078/1990).\r\n\r\n1. Da Renovação Automática\r\n\r\n1.1. A assinatura do serviço contratado possui renovação automática ao final de cada período contratado, pelo mesmo prazo e nas mesmas condições vigentes no momento da renovação, salvo comunicação em contrário pelo CLIENTE.\r\n\r\n1.2. A renovação automática tem como objetivo garantir a continuidade do acesso ao serviço, evitando interrupções não desejadas.\r\n\r\n2. Da Informação Clara e Transparente\r\n\r\n2.1. O CLIENTE declara estar ciente de forma prévia, clara, precisa e ostensiva sobre:\r\n\r\nO valor da assinatura;\r\n\r\nO período de vigência;\r\n\r\nA forma de cobrança;\r\n\r\nA existência da renovação automática.\r\n\r\n2.2. Qualquer alteração relevante de preço ou condições será informada previamente ao CLIENTE, respeitando a legislação aplicável.\r\n\r\n3. Do Consentimento Expresso\r\n\r\n3.1. A renovação automática não configura serviço não solicitado, uma vez que o CLIENTE manifesta seu consentimento expresso ao aceitar este termo no momento da contratação.\r\n\r\n3.2. Este consentimento poderá ser revogado a qualquer momento, conforme disposto neste termo.\r\n\r\n4. Do Direito de Cancelamento\r\n\r\n4.1. O CLIENTE poderá desativar a renovação automática ou cancelar a assinatura a qualquer tempo, de forma simples, imediata e sem a imposição de obstáculos indevidos, por meio da área administrativa da plataforma ou outro canal disponibilizado.\r\n\r\n4.2. O cancelamento da renovação automática não gera penalidades, taxas adicionais ou ônus desproporcionais ao CLIENTE, respeitando o disposto nos artigos 39 e 51 do Código de Defesa do Consumidor.\r\n\r\n4.3. Após o cancelamento, o CLIENTE manterá o acesso ao serviço até o término do período já pago, não sendo realizadas novas cobranças.\r\n\r\n5. Da Fidelidade\r\n\r\n5.1. Caso exista período de fidelidade, o CLIENTE será informado de forma destacada.\r\n\r\n5.2. A renovação automática de eventual período de fidelidade exigirá novo consentimento expresso do CLIENTE, não sendo presumida em nenhuma hipótese.\r\n\r\n6. Da Conformidade Legal\r\n\r\n6.1. Este termo está em conformidade com o Código de Defesa do Consumidor, não contendo cláusulas abusivas ou que coloquem o CLIENTE em desvantagem exagerada.\r\n\r\n6.2. Em caso de dúvidas, o CLIENTE poderá buscar orientação junto a órgãos de defesa do consumidor, como o PROCON ou o IDEC, sem prejuízo do direito de acesso ao Poder Judiciário.\r\n\r\n7. Do Aceite\r\n\r\n7.1. Ao prosseguir com a contratação e/ou utilização do serviço, o CLIENTE declara que leu, compreendeu e concorda integralmente com este Termo de Consentimento para Renovação Automática.', '2026-02-05 19:13:20', '45.171.13.34', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'customer', 'web', NULL),
(20, 27, 'v1.0', 'd4691b5b05793339745b964034e53a732c491052b2b8ad32a038cfd607424b6e', 'Termos de Renovação Automática', 'Ao contratar e utilizar os serviços disponibilizados pela plataforma, o CLIENTE declara que leu, compreendeu e concorda expressamente com os termos abaixo relacionados à renovação automática da assinatura, nos termos da legislação brasileira vigente, especialmente o Código de Defesa do Consumidor (Lei nº 8.078/1990).\r\n\r\n1. Da Renovação Automática\r\n\r\n1.1. A assinatura do serviço contratado possui renovação automática ao final de cada período contratado, pelo mesmo prazo e nas mesmas condições vigentes no momento da renovação, salvo comunicação em contrário pelo CLIENTE.\r\n\r\n1.2. A renovação automática tem como objetivo garantir a continuidade do acesso ao serviço, evitando interrupções não desejadas.\r\n\r\n2. Da Informação Clara e Transparente\r\n\r\n2.1. O CLIENTE declara estar ciente de forma prévia, clara, precisa e ostensiva sobre:\r\n\r\nO valor da assinatura;\r\n\r\nO período de vigência;\r\n\r\nA forma de cobrança;\r\n\r\nA existência da renovação automática.\r\n\r\n2.2. Qualquer alteração relevante de preço ou condições será informada previamente ao CLIENTE, respeitando a legislação aplicável.\r\n\r\n3. Do Consentimento Expresso\r\n\r\n3.1. A renovação automática não configura serviço não solicitado, uma vez que o CLIENTE manifesta seu consentimento expresso ao aceitar este termo no momento da contratação.\r\n\r\n3.2. Este consentimento poderá ser revogado a qualquer momento, conforme disposto neste termo.\r\n\r\n4. Do Direito de Cancelamento\r\n\r\n4.1. O CLIENTE poderá desativar a renovação automática ou cancelar a assinatura a qualquer tempo, de forma simples, imediata e sem a imposição de obstáculos indevidos, por meio da área administrativa da plataforma ou outro canal disponibilizado.\r\n\r\n4.2. O cancelamento da renovação automática não gera penalidades, taxas adicionais ou ônus desproporcionais ao CLIENTE, respeitando o disposto nos artigos 39 e 51 do Código de Defesa do Consumidor.\r\n\r\n4.3. Após o cancelamento, o CLIENTE manterá o acesso ao serviço até o término do período já pago, não sendo realizadas novas cobranças.\r\n\r\n5. Da Fidelidade\r\n\r\n5.1. Caso exista período de fidelidade, o CLIENTE será informado de forma destacada.\r\n\r\n5.2. A renovação automática de eventual período de fidelidade exigirá novo consentimento expresso do CLIENTE, não sendo presumida em nenhuma hipótese.\r\n\r\n6. Da Conformidade Legal\r\n\r\n6.1. Este termo está em conformidade com o Código de Defesa do Consumidor, não contendo cláusulas abusivas ou que coloquem o CLIENTE em desvantagem exagerada.\r\n\r\n6.2. Em caso de dúvidas, o CLIENTE poderá buscar orientação junto a órgãos de defesa do consumidor, como o PROCON ou o IDEC, sem prejuízo do direito de acesso ao Poder Judiciário.\r\n\r\n7. Do Aceite\r\n\r\n7.1. Ao prosseguir com a contratação e/ou utilização do serviço, o CLIENTE declara que leu, compreendeu e concorda integralmente com este Termo de Consentimento para Renovação Automática.', '2026-02-07 17:53:09', '45.171.13.34', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'customer', 'web', NULL),
(21, 27, 'v1.0', 'd4691b5b05793339745b964034e53a732c491052b2b8ad32a038cfd607424b6e', 'Termos de Renovação Automática', 'Ao contratar e utilizar os serviços disponibilizados pela plataforma, o CLIENTE declara que leu, compreendeu e concorda expressamente com os termos abaixo relacionados à renovação automática da assinatura, nos termos da legislação brasileira vigente, especialmente o Código de Defesa do Consumidor (Lei nº 8.078/1990).\r\n\r\n1. Da Renovação Automática\r\n\r\n1.1. A assinatura do serviço contratado possui renovação automática ao final de cada período contratado, pelo mesmo prazo e nas mesmas condições vigentes no momento da renovação, salvo comunicação em contrário pelo CLIENTE.\r\n\r\n1.2. A renovação automática tem como objetivo garantir a continuidade do acesso ao serviço, evitando interrupções não desejadas.\r\n\r\n2. Da Informação Clara e Transparente\r\n\r\n2.1. O CLIENTE declara estar ciente de forma prévia, clara, precisa e ostensiva sobre:\r\n\r\nO valor da assinatura;\r\n\r\nO período de vigência;\r\n\r\nA forma de cobrança;\r\n\r\nA existência da renovação automática.\r\n\r\n2.2. Qualquer alteração relevante de preço ou condições será informada previamente ao CLIENTE, respeitando a legislação aplicável.\r\n\r\n3. Do Consentimento Expresso\r\n\r\n3.1. A renovação automática não configura serviço não solicitado, uma vez que o CLIENTE manifesta seu consentimento expresso ao aceitar este termo no momento da contratação.\r\n\r\n3.2. Este consentimento poderá ser revogado a qualquer momento, conforme disposto neste termo.\r\n\r\n4. Do Direito de Cancelamento\r\n\r\n4.1. O CLIENTE poderá desativar a renovação automática ou cancelar a assinatura a qualquer tempo, de forma simples, imediata e sem a imposição de obstáculos indevidos, por meio da área administrativa da plataforma ou outro canal disponibilizado.\r\n\r\n4.2. O cancelamento da renovação automática não gera penalidades, taxas adicionais ou ônus desproporcionais ao CLIENTE, respeitando o disposto nos artigos 39 e 51 do Código de Defesa do Consumidor.\r\n\r\n4.3. Após o cancelamento, o CLIENTE manterá o acesso ao serviço até o término do período já pago, não sendo realizadas novas cobranças.\r\n\r\n5. Da Fidelidade\r\n\r\n5.1. Caso exista período de fidelidade, o CLIENTE será informado de forma destacada.\r\n\r\n5.2. A renovação automática de eventual período de fidelidade exigirá novo consentimento expresso do CLIENTE, não sendo presumida em nenhuma hipótese.\r\n\r\n6. Da Conformidade Legal\r\n\r\n6.1. Este termo está em conformidade com o Código de Defesa do Consumidor, não contendo cláusulas abusivas ou que coloquem o CLIENTE em desvantagem exagerada.\r\n\r\n6.2. Em caso de dúvidas, o CLIENTE poderá buscar orientação junto a órgãos de defesa do consumidor, como o PROCON ou o IDEC, sem prejuízo do direito de acesso ao Poder Judiciário.\r\n\r\n7. Do Aceite\r\n\r\n7.1. Ao prosseguir com a contratação e/ou utilização do serviço, o CLIENTE declara que leu, compreendeu e concorda integralmente com este Termo de Consentimento para Renovação Automática.', '2026-02-07 18:10:46', '45.171.13.34', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'customer', 'web', NULL),
(22, 1, 'v1.0', 'd4691b5b05793339745b964034e53a732c491052b2b8ad32a038cfd607424b6e', 'Termos de Renovação Automática', 'Ao contratar e utilizar os serviços disponibilizados pela plataforma, o CLIENTE declara que leu, compreendeu e concorda expressamente com os termos abaixo relacionados à renovação automática da assinatura, nos termos da legislação brasileira vigente, especialmente o Código de Defesa do Consumidor (Lei nº 8.078/1990).\r\n\r\n1. Da Renovação Automática\r\n\r\n1.1. A assinatura do serviço contratado possui renovação automática ao final de cada período contratado, pelo mesmo prazo e nas mesmas condições vigentes no momento da renovação, salvo comunicação em contrário pelo CLIENTE.\r\n\r\n1.2. A renovação automática tem como objetivo garantir a continuidade do acesso ao serviço, evitando interrupções não desejadas.\r\n\r\n2. Da Informação Clara e Transparente\r\n\r\n2.1. O CLIENTE declara estar ciente de forma prévia, clara, precisa e ostensiva sobre:\r\n\r\nO valor da assinatura;\r\n\r\nO período de vigência;\r\n\r\nA forma de cobrança;\r\n\r\nA existência da renovação automática.\r\n\r\n2.2. Qualquer alteração relevante de preço ou condições será informada previamente ao CLIENTE, respeitando a legislação aplicável.\r\n\r\n3. Do Consentimento Expresso\r\n\r\n3.1. A renovação automática não configura serviço não solicitado, uma vez que o CLIENTE manifesta seu consentimento expresso ao aceitar este termo no momento da contratação.\r\n\r\n3.2. Este consentimento poderá ser revogado a qualquer momento, conforme disposto neste termo.\r\n\r\n4. Do Direito de Cancelamento\r\n\r\n4.1. O CLIENTE poderá desativar a renovação automática ou cancelar a assinatura a qualquer tempo, de forma simples, imediata e sem a imposição de obstáculos indevidos, por meio da área administrativa da plataforma ou outro canal disponibilizado.\r\n\r\n4.2. O cancelamento da renovação automática não gera penalidades, taxas adicionais ou ônus desproporcionais ao CLIENTE, respeitando o disposto nos artigos 39 e 51 do Código de Defesa do Consumidor.\r\n\r\n4.3. Após o cancelamento, o CLIENTE manterá o acesso ao serviço até o término do período já pago, não sendo realizadas novas cobranças.\r\n\r\n5. Da Fidelidade\r\n\r\n5.1. Caso exista período de fidelidade, o CLIENTE será informado de forma destacada.\r\n\r\n5.2. A renovação automática de eventual período de fidelidade exigirá novo consentimento expresso do CLIENTE, não sendo presumida em nenhuma hipótese.\r\n\r\n6. Da Conformidade Legal\r\n\r\n6.1. Este termo está em conformidade com o Código de Defesa do Consumidor, não contendo cláusulas abusivas ou que coloquem o CLIENTE em desvantagem exagerada.\r\n\r\n6.2. Em caso de dúvidas, o CLIENTE poderá buscar orientação junto a órgãos de defesa do consumidor, como o PROCON ou o IDEC, sem prejuízo do direito de acesso ao Poder Judiciário.\r\n\r\n7. Do Aceite\r\n\r\n7.1. Ao prosseguir com a contratação e/ou utilização do serviço, o CLIENTE declara que leu, compreendeu e concorda integralmente com este Termo de Consentimento para Renovação Automática.', '2026-02-07 18:20:58', '45.171.13.34', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'customer', 'web', NULL),
(23, 33, 'v1.0', 'd4691b5b05793339745b964034e53a732c491052b2b8ad32a038cfd607424b6e', 'Termos de Renovação Automática', 'Ao contratar e utilizar os serviços disponibilizados pela plataforma, o CLIENTE declara que leu, compreendeu e concorda expressamente com os termos abaixo relacionados à renovação automática da assinatura, nos termos da legislação brasileira vigente, especialmente o Código de Defesa do Consumidor (Lei nº 8.078/1990).\r\n\r\n1. Da Renovação Automática\r\n\r\n1.1. A assinatura do serviço contratado possui renovação automática ao final de cada período contratado, pelo mesmo prazo e nas mesmas condições vigentes no momento da renovação, salvo comunicação em contrário pelo CLIENTE.\r\n\r\n1.2. A renovação automática tem como objetivo garantir a continuidade do acesso ao serviço, evitando interrupções não desejadas.\r\n\r\n2. Da Informação Clara e Transparente\r\n\r\n2.1. O CLIENTE declara estar ciente de forma prévia, clara, precisa e ostensiva sobre:\r\n\r\nO valor da assinatura;\r\n\r\nO período de vigência;\r\n\r\nA forma de cobrança;\r\n\r\nA existência da renovação automática.\r\n\r\n2.2. Qualquer alteração relevante de preço ou condições será informada previamente ao CLIENTE, respeitando a legislação aplicável.\r\n\r\n3. Do Consentimento Expresso\r\n\r\n3.1. A renovação automática não configura serviço não solicitado, uma vez que o CLIENTE manifesta seu consentimento expresso ao aceitar este termo no momento da contratação.\r\n\r\n3.2. Este consentimento poderá ser revogado a qualquer momento, conforme disposto neste termo.\r\n\r\n4. Do Direito de Cancelamento\r\n\r\n4.1. O CLIENTE poderá desativar a renovação automática ou cancelar a assinatura a qualquer tempo, de forma simples, imediata e sem a imposição de obstáculos indevidos, por meio da área administrativa da plataforma ou outro canal disponibilizado.\r\n\r\n4.2. O cancelamento da renovação automática não gera penalidades, taxas adicionais ou ônus desproporcionais ao CLIENTE, respeitando o disposto nos artigos 39 e 51 do Código de Defesa do Consumidor.\r\n\r\n4.3. Após o cancelamento, o CLIENTE manterá o acesso ao serviço até o término do período já pago, não sendo realizadas novas cobranças.\r\n\r\n5. Da Fidelidade\r\n\r\n5.1. Caso exista período de fidelidade, o CLIENTE será informado de forma destacada.\r\n\r\n5.2. A renovação automática de eventual período de fidelidade exigirá novo consentimento expresso do CLIENTE, não sendo presumida em nenhuma hipótese.\r\n\r\n6. Da Conformidade Legal\r\n\r\n6.1. Este termo está em conformidade com o Código de Defesa do Consumidor, não contendo cláusulas abusivas ou que coloquem o CLIENTE em desvantagem exagerada.\r\n\r\n6.2. Em caso de dúvidas, o CLIENTE poderá buscar orientação junto a órgãos de defesa do consumidor, como o PROCON ou o IDEC, sem prejuízo do direito de acesso ao Poder Judiciário.\r\n\r\n7. Do Aceite\r\n\r\n7.1. Ao prosseguir com a contratação e/ou utilização do serviço, o CLIENTE declara que leu, compreendeu e concorda integralmente com este Termo de Consentimento para Renovação Automática.', '2026-02-07 20:42:34', '45.171.13.34', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'customer', 'web', NULL),
(24, 1, 'v1.0', 'd4691b5b05793339745b964034e53a732c491052b2b8ad32a038cfd607424b6e', 'Termos de Renovação Automática', 'Ao contratar e utilizar os serviços disponibilizados pela plataforma, o CLIENTE declara que leu, compreendeu e concorda expressamente com os termos abaixo relacionados à renovação automática da assinatura, nos termos da legislação brasileira vigente, especialmente o Código de Defesa do Consumidor (Lei nº 8.078/1990).\r\n\r\n1. Da Renovação Automática\r\n\r\n1.1. A assinatura do serviço contratado possui renovação automática ao final de cada período contratado, pelo mesmo prazo e nas mesmas condições vigentes no momento da renovação, salvo comunicação em contrário pelo CLIENTE.\r\n\r\n1.2. A renovação automática tem como objetivo garantir a continuidade do acesso ao serviço, evitando interrupções não desejadas.\r\n\r\n2. Da Informação Clara e Transparente\r\n\r\n2.1. O CLIENTE declara estar ciente de forma prévia, clara, precisa e ostensiva sobre:\r\n\r\nO valor da assinatura;\r\n\r\nO período de vigência;\r\n\r\nA forma de cobrança;\r\n\r\nA existência da renovação automática.\r\n\r\n2.2. Qualquer alteração relevante de preço ou condições será informada previamente ao CLIENTE, respeitando a legislação aplicável.\r\n\r\n3. Do Consentimento Expresso\r\n\r\n3.1. A renovação automática não configura serviço não solicitado, uma vez que o CLIENTE manifesta seu consentimento expresso ao aceitar este termo no momento da contratação.\r\n\r\n3.2. Este consentimento poderá ser revogado a qualquer momento, conforme disposto neste termo.\r\n\r\n4. Do Direito de Cancelamento\r\n\r\n4.1. O CLIENTE poderá desativar a renovação automática ou cancelar a assinatura a qualquer tempo, de forma simples, imediata e sem a imposição de obstáculos indevidos, por meio da área administrativa da plataforma ou outro canal disponibilizado.\r\n\r\n4.2. O cancelamento da renovação automática não gera penalidades, taxas adicionais ou ônus desproporcionais ao CLIENTE, respeitando o disposto nos artigos 39 e 51 do Código de Defesa do Consumidor.\r\n\r\n4.3. Após o cancelamento, o CLIENTE manterá o acesso ao serviço até o término do período já pago, não sendo realizadas novas cobranças.\r\n\r\n5. Da Fidelidade\r\n\r\n5.1. Caso exista período de fidelidade, o CLIENTE será informado de forma destacada.\r\n\r\n5.2. A renovação automática de eventual período de fidelidade exigirá novo consentimento expresso do CLIENTE, não sendo presumida em nenhuma hipótese.\r\n\r\n6. Da Conformidade Legal\r\n\r\n6.1. Este termo está em conformidade com o Código de Defesa do Consumidor, não contendo cláusulas abusivas ou que coloquem o CLIENTE em desvantagem exagerada.\r\n\r\n6.2. Em caso de dúvidas, o CLIENTE poderá buscar orientação junto a órgãos de defesa do consumidor, como o PROCON ou o IDEC, sem prejuízo do direito de acesso ao Poder Judiciário.\r\n\r\n7. Do Aceite\r\n\r\n7.1. Ao prosseguir com a contratação e/ou utilização do serviço, o CLIENTE declara que leu, compreendeu e concorda integralmente com este Termo de Consentimento para Renovação Automática.', '2026-02-08 18:04:27', '45.171.13.34', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'customer', 'web', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbsys_signatures_history`
--

CREATE TABLE `tbsys_signatures_history` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `signature_id` bigint(255) UNSIGNED NOT NULL,
  `old_access_plan_id` bigint(255) UNSIGNED DEFAULT NULL,
  `new_access_plan_id` bigint(255) UNSIGNED NOT NULL,
  `old_status` varchar(30) DEFAULT NULL,
  `new_status` varchar(30) DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `changed_by` varchar(50) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbsys_signatures_payments`
--

CREATE TABLE `tbsys_signatures_payments` (
  `id` bigint(255) NOT NULL,
  `gcid` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `signature_id` bigint(255) NOT NULL,
  `date_billing` datetime NOT NULL,
  `date_due` datetime DEFAULT NULL,
  `date_payment` datetime DEFAULT NULL,
  `payment_token` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `card_mask` varchar(25) DEFAULT NULL,
  `installment` int(5) DEFAULT NULL,
  `payment_status_id` int(2) DEFAULT NULL,
  `payment_status` varchar(15) DEFAULT NULL,
  `payment_config_id` int(1) DEFAULT NULL,
  `payment_charge_id` text DEFAULT NULL,
  `payment_method_id` int(2) DEFAULT NULL,
  `payment_method` varchar(15) DEFAULT NULL,
  `nfse_sent` tinyint(1) NOT NULL DEFAULT 0,
  `nfse_issued` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `user_id_updated` bigint(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Despejando dados para a tabela `tbsys_signatures_payments`
--

INSERT INTO `tbsys_signatures_payments` (`id`, `gcid`, `signature_id`, `date_billing`, `date_due`, `date_payment`, `payment_token`, `card_mask`, `installment`, `payment_status_id`, `payment_status`, `payment_config_id`, `payment_charge_id`, `payment_method_id`, `payment_method`, `nfse_sent`, `nfse_issued`, `created_at`, `updated_at`, `user_id_updated`) VALUES
(20, '54cf766a-b45d-4924-ae69-ef7b2febaaeb', 1, '2026-01-05 17:19:10', '2026-01-04 17:19:10', '2026-01-05 17:19:11', 'c4889e4e039d4d77776661c6dbb1030366ad0157', '448578XXXXXX0087', 1, 4, 'paid', 1, '44894485', 1, 'credit_card', 0, 0, '2026-02-05 17:19:10', '2026-02-05 17:29:17', NULL),
(26, '1cbea12a-fce7-4705-8296-c42d30b6599d', 27, '2026-01-05 19:13:18', '2025-01-10 19:13:18', '2026-02-05 16:32:10', '3eb479682199106e57c2699acb0afcf0351aed96', '448578XXXXXX0087', 10, 4, 'paid', 1, '44897363', 1, 'credit_card', 0, 0, '2026-02-05 19:13:18', '2026-02-07 16:42:17', NULL),
(28, '7d977767-b4e0-4dff-9428-8b0621f468c8', 29, '2026-02-05 19:27:55', '2026-02-10 19:27:55', '2026-02-05 19:27:56', '4b6f28cb540308eff61ce3f0cf5854a3b5885e39', '448578XXXXXX0087', 1, 4, 'paid', 1, '44894704', 1, 'credit_card', 0, 0, '2026-02-05 19:27:55', '2026-02-05 19:38:03', NULL),
(31, '9e10d7e4-21bc-47cd-9934-4f8040cb0249', 27, '2026-02-07 16:16:16', '2026-02-12 16:16:16', '2026-02-07 18:10:46', '8cbabff3fbb44a1db62565e4e8faa58515752dff', '448578XXXXXX0087', 10, 4, 'paid', 1, '44897373', 1, 'credit_card', 0, 0, '2026-02-07 16:16:16', '2026-02-07 18:20:52', NULL),
(32, '3a4a118a-e7f6-4a0a-bc45-ac675e3c70f4', 1, '2026-01-07 18:19:25', '2026-01-12 18:19:25', '2025-01-07 18:20:58', '9dd2b3391d684407e2193904b3ca82b8f367c105', '448578XXXXXX0087', 10, 4, 'paid', 1, '44897374', 1, 'credit_card', 0, 0, '2026-02-07 18:19:25', '2026-02-07 18:31:05', NULL),
(33, '026ebbc4-3969-4cb2-98c8-385618ab7383', 1, '2026-02-07 19:54:37', '2026-02-12 19:54:37', '2026-02-08 18:04:27', 'd77afde571304f136c618d6539e3540f1a151d9f', '448578XXXXXX0087', 10, 4, 'paid', 1, '44897722', 1, 'credit_card', 0, 0, '2026-02-07 19:54:37', '2026-02-08 18:14:33', NULL),
(36, 'a901cb12-8442-41c5-9527-573fdac49cfd', 33, '2026-02-07 20:42:34', '2026-02-12 20:42:34', '2026-02-07 20:42:34', 'ff003e6fafe52f7b07d4d3c1f7f1ee03328db2f6', '448578XXXXXX0087', 10, 4, 'paid', 1, '44897405', 1, 'credit_card', 0, 0, '2026-02-07 20:42:34', '2026-02-07 20:52:40', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbsys_signatures_payments_invoices`
--

CREATE TABLE `tbsys_signatures_payments_invoices` (
  `id` bigint(255) NOT NULL,
  `gcid` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `signature_payment_gcid` text NOT NULL,
  `number_invoice` text DEFAULT NULL,
  `verification_code` text DEFAULT NULL,
  `series_invoice` text DEFAULT NULL,
  `date_issue` date DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `net_amount` decimal(10,2) DEFAULT NULL,
  `consultation_url` text DEFAULT NULL,
  `canceled_at` date DEFAULT NULL,
  `cancel_reason` text DEFAULT NULL,
  `invoice_xml` text DEFAULT NULL,
  `invoice_pdf` text DEFAULT NULL,
  `user_id_created` bigint(255) DEFAULT NULL,
  `user_id_updated` bigint(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Despejando dados para a tabela `tbsys_signatures_payments_invoices`
--

INSERT INTO `tbsys_signatures_payments_invoices` (`id`, `gcid`, `signature_payment_gcid`, `number_invoice`, `verification_code`, `series_invoice`, `date_issue`, `total_amount`, `net_amount`, `consultation_url`, `canceled_at`, `cancel_reason`, `invoice_xml`, `invoice_pdf`, `user_id_created`, `user_id_updated`, `created_at`, `updated_at`) VALUES
(12, 'bf444bf4-7e2b-4396-9884-181c55e42216', '54cf766a-b45d-4924-ae69-ef7b2febaaeb', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-05 17:19:10', NULL),
(18, '9a7c3929-a2af-4847-926f-7842a96fb29d', '1cbea12a-fce7-4705-8296-c42d30b6599d', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-05 19:13:18', NULL),
(20, '669385fb-1ccc-4461-8c45-5aec897742e9', '7d977767-b4e0-4dff-9428-8b0621f468c8', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-05 19:27:55', NULL),
(21, 'fad7f1af-1c26-4a35-bb88-b32bbe7e0cab', '2a687acd-77dc-4ce9-b5a3-2450dfb64c83', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-07 20:36:16', NULL),
(22, 'bffc14e6-66be-4caa-b44e-c2b56dec2150', 'cdda1601-c4d0-4a34-adee-2ee1f1db0984', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-07 20:40:47', NULL),
(23, '455debd5-ea2d-4fb8-a6f9-47fb7ecd448e', 'a901cb12-8442-41c5-9527-573fdac49cfd', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-07 20:42:34', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbsys_signatures_payments_status_history`
--

CREATE TABLE `tbsys_signatures_payments_status_history` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `signature_payment_id` bigint(255) UNSIGNED NOT NULL,
  `old_status` varchar(50) DEFAULT NULL,
  `new_status` varchar(50) NOT NULL,
  `changed_by` varchar(50) NOT NULL COMMENT 'system | webhook | admin',
  `reason` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `tbsys_signatures_payments_status_history`
--

INSERT INTO `tbsys_signatures_payments_status_history` (`id`, `signature_payment_id`, `old_status`, `new_status`, `changed_by`, `reason`, `created_at`) VALUES
(27, 20, 'waiting', 'approved', 'webhook', '1->Pagamento aprovado! Estamos aguardando a liberação pela operadora do cartão. Acompanhe o andamento no menu faturas.', '2026-02-05 20:19:17'),
(28, 20, 'approved', 'paid', 'webhook', '1->Sucesso! Seu pagamento foi confirmado.', '2026-02-05 20:29:17'),
(35, 26, 'waiting', 'approved', 'webhook', '1->Pagamento aprovado! Estamos aguardando a liberação pela operadora do cartão. Acompanhe o andamento no menu faturas.', '2026-02-05 22:13:26'),
(36, 26, 'approved', 'paid', 'webhook', '1->Sucesso! Seu pagamento foi confirmado.', '2026-02-05 22:23:26'),
(37, 28, 'waiting', 'approved', 'webhook', '1->Pagamento aprovado! Estamos aguardando a liberação pela operadora do cartão. Acompanhe o andamento no menu faturas.', '2026-02-05 22:28:02'),
(38, 28, 'approved', 'paid', 'webhook', '1->Sucesso! Seu pagamento foi confirmado.', '2026-02-05 22:38:03'),
(39, 26, 'waiting', 'approved', 'webhook', '1->Pagamento aprovado! Estamos aguardando a liberação pela operadora do cartão. Acompanhe o andamento no menu faturas.', '2026-02-07 19:32:16'),
(40, 26, 'approved', 'paid', 'webhook', '1->Sucesso! Seu pagamento foi confirmado.', '2026-02-07 19:42:17'),
(41, 31, 'approved', 'paid', 'webhook', '1->Sucesso! Seu pagamento foi confirmado.', '2026-02-07 20:18:35'),
(42, 31, 'approved', 'paid', 'webhook', '1->Sucesso! Seu pagamento foi confirmado.', '2026-02-07 20:18:35'),
(43, 31, 'waiting', 'approved', 'webhook', '1->Pagamento aprovado! Estamos aguardando a liberação pela operadora do cartão. Acompanhe o andamento no menu faturas.', '2026-02-07 20:53:15'),
(44, 31, 'waiting', 'approved', 'webhook', '1->Pagamento aprovado! Estamos aguardando a liberação pela operadora do cartão. Acompanhe o andamento no menu faturas.', '2026-02-07 21:10:52'),
(45, 31, 'approved', 'paid', 'webhook', '1->Sucesso! Seu pagamento foi confirmado.', '2026-02-07 21:20:52'),
(46, 32, 'waiting', 'approved', 'webhook', '1->Pagamento aprovado! Estamos aguardando a liberação pela operadora do cartão. Acompanhe o andamento no menu faturas.', '2026-02-07 21:21:04'),
(47, 32, 'approved', 'paid', 'webhook', '1->Sucesso! Seu pagamento foi confirmado.', '2026-02-07 21:31:05'),
(48, 35, 'waiting', 'approved', 'webhook', '1->Pagamento aprovado! Estamos aguardando a liberação pela operadora do cartão. Acompanhe o andamento no menu faturas.', '2026-02-07 23:40:55'),
(49, 36, 'waiting', 'approved', 'webhook', '1->Pagamento aprovado! Estamos aguardando a liberação pela operadora do cartão. Acompanhe o andamento no menu faturas.', '2026-02-07 23:42:40'),
(50, 36, 'approved', 'paid', 'webhook', '1->Sucesso! Seu pagamento foi confirmado.', '2026-02-07 23:52:40'),
(51, 33, 'waiting', 'approved', 'webhook', '1->Pagamento aprovado! Estamos aguardando a liberação pela operadora do cartão. Acompanhe o andamento no menu faturas.', '2026-02-08 21:04:33'),
(52, 33, 'approved', 'paid', 'webhook', '1->Sucesso! Seu pagamento foi confirmado.', '2026-02-08 21:14:33');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbsys_signatures_plan_changes`
--

CREATE TABLE `tbsys_signatures_plan_changes` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `signature_id` bigint(255) UNSIGNED NOT NULL,
  `current_access_plan_id` bigint(255) UNSIGNED NOT NULL,
  `new_access_plan_id` bigint(255) UNSIGNED NOT NULL,
  `effective_date` date NOT NULL,
  `status` enum('pending','applied','canceled') DEFAULT 'pending',
  `created_at` datetime DEFAULT current_timestamp(),
  `applied_at` datetime DEFAULT NULL,
  `user_id` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbsys_signatures_terms`
--

CREATE TABLE `tbsys_signatures_terms` (
  `id` int(2) NOT NULL,
  `version` varchar(20) NOT NULL,
  `title` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `term` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `user_id_created` bigint(255) DEFAULT NULL,
  `user_id_updated` bigint(255) DEFAULT NULL,
  `status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Despejando dados para a tabela `tbsys_signatures_terms`
--

INSERT INTO `tbsys_signatures_terms` (`id`, `version`, `title`, `term`, `type`, `created_at`, `updated_at`, `user_id_created`, `user_id_updated`, `status`) VALUES
(1, 'v1.0', 'Termos de Renovação Automática', 'Ao contratar e utilizar os serviços disponibilizados pela plataforma, o CLIENTE declara que leu, compreendeu e concorda expressamente com os termos abaixo relacionados à renovação automática da assinatura, nos termos da legislação brasileira vigente, especialmente o Código de Defesa do Consumidor (Lei nº 8.078/1990).\r\n\r\n1. Da Renovação Automática\r\n\r\n1.1. A assinatura do serviço contratado possui renovação automática ao final de cada período contratado, pelo mesmo prazo e nas mesmas condições vigentes no momento da renovação, salvo comunicação em contrário pelo CLIENTE.\r\n\r\n1.2. A renovação automática tem como objetivo garantir a continuidade do acesso ao serviço, evitando interrupções não desejadas.\r\n\r\n2. Da Informação Clara e Transparente\r\n\r\n2.1. O CLIENTE declara estar ciente de forma prévia, clara, precisa e ostensiva sobre:\r\n\r\nO valor da assinatura;\r\n\r\nO período de vigência;\r\n\r\nA forma de cobrança;\r\n\r\nA existência da renovação automática.\r\n\r\n2.2. Qualquer alteração relevante de preço ou condições será informada previamente ao CLIENTE, respeitando a legislação aplicável.\r\n\r\n3. Do Consentimento Expresso\r\n\r\n3.1. A renovação automática não configura serviço não solicitado, uma vez que o CLIENTE manifesta seu consentimento expresso ao aceitar este termo no momento da contratação.\r\n\r\n3.2. Este consentimento poderá ser revogado a qualquer momento, conforme disposto neste termo.\r\n\r\n4. Do Direito de Cancelamento\r\n\r\n4.1. O CLIENTE poderá desativar a renovação automática ou cancelar a assinatura a qualquer tempo, de forma simples, imediata e sem a imposição de obstáculos indevidos, por meio da área administrativa da plataforma ou outro canal disponibilizado.\r\n\r\n4.2. O cancelamento da renovação automática não gera penalidades, taxas adicionais ou ônus desproporcionais ao CLIENTE, respeitando o disposto nos artigos 39 e 51 do Código de Defesa do Consumidor.\r\n\r\n4.3. Após o cancelamento, o CLIENTE manterá o acesso ao serviço até o término do período já pago, não sendo realizadas novas cobranças.\r\n\r\n5. Da Fidelidade\r\n\r\n5.1. Caso exista período de fidelidade, o CLIENTE será informado de forma destacada.\r\n\r\n5.2. A renovação automática de eventual período de fidelidade exigirá novo consentimento expresso do CLIENTE, não sendo presumida em nenhuma hipótese.\r\n\r\n6. Da Conformidade Legal\r\n\r\n6.1. Este termo está em conformidade com o Código de Defesa do Consumidor, não contendo cláusulas abusivas ou que coloquem o CLIENTE em desvantagem exagerada.\r\n\r\n6.2. Em caso de dúvidas, o CLIENTE poderá buscar orientação junto a órgãos de defesa do consumidor, como o PROCON ou o IDEC, sem prejuízo do direito de acesso ao Poder Judiciário.\r\n\r\n7. Do Aceite\r\n\r\n7.1. Ao prosseguir com a contratação e/ou utilização do serviço, o CLIENTE declara que leu, compreendeu e concorda integralmente com este Termo de Consentimento para Renovação Automática.', 'terms_automatic_renewal', '2026-01-09 20:25:40', NULL, 2, NULL, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbsys_stconfig`
--

CREATE TABLE `tbsys_stconfig` (
  `id` int(2) NOT NULL,
  `title` text NOT NULL,
  `ico` text NOT NULL,
  `favicon` varchar(30) DEFAULT NULL,
  `footer` text NOT NULL,
  `logo` text NOT NULL,
  `tag_key_words` text NOT NULL,
  `tag_description` text NOT NULL,
  `tag_title` text NOT NULL,
  `whatsapp` text NOT NULL,
  `maintenance` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Despejando dados para a tabela `tbsys_stconfig`
--

INSERT INTO `tbsys_stconfig` (`id`, `title`, `ico`, `favicon`, `footer`, `logo`, `tag_key_words`, `tag_description`, `tag_title`, `whatsapp`, `maintenance`) VALUES
(1, 'TheTecInfor', 'thetec.png', 'thetec.ico', '<strong>Copyright © \n<a href=\'https://thetecinfor.com\'><i class=\'nav-icon fas fa-code\'></i> TheTecInfor <i class=\'nav-icon fas fa-code\'></i></a></strong> Todos os direitos reservados.', 'logo.png', 'desenvolvimento, programação, seo, aplicativos, sites, merketing, publicidade, social, empresas', 'Somos uma empresa focada em levar a tecnologia da informação ao seu alcance, desenvolvendo através da programação aplicativos e sites responsivo para todos os tipos de dispositivos.', 'TheTecInfor - Levando a tecnologia da informação ao seu alcance', '', 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbsys_ticket`
--

CREATE TABLE `tbsys_ticket` (
  `id` bigint(255) NOT NULL,
  `gcid` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `customer_id` bigint(255) NOT NULL,
  `ticket_department_subdepartment_id` int(3) NOT NULL,
  `title` varchar(80) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `priority_id` int(2) NOT NULL,
  `level` int(2) NOT NULL,
  `date_send` datetime NOT NULL,
  `date_closing` datetime DEFAULT NULL,
  `response` int(1) NOT NULL DEFAULT 1,
  `message_reading_status` int(1) DEFAULT NULL,
  `user_id_reading` bigint(255) DEFAULT NULL,
  `date_reading` datetime DEFAULT NULL,
  `status` int(1) NOT NULL,
  `closure_description` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `user_id_updated` bigint(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Despejando dados para a tabela `tbsys_ticket`
--

INSERT INTO `tbsys_ticket` (`id`, `gcid`, `customer_id`, `ticket_department_subdepartment_id`, `title`, `description`, `priority_id`, `level`, `date_send`, `date_closing`, `response`, `message_reading_status`, `user_id_reading`, `date_reading`, `status`, `closure_description`, `created_at`, `updated_at`, `user_id_updated`) VALUES
(1, '7192e5a9-f495-4951-89a1-ff5c285256f1', 1, 1, 'teste', 'teste2', 5, 5, '2025-08-22 00:29:30', '2025-12-31 12:09:00', 0, 0, 2, '2025-09-13 11:07:52', 1, 'resolvido', '2025-09-17 13:40:42', '2025-12-31 12:33:17', 2);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbsys_ticket_department`
--

CREATE TABLE `tbsys_ticket_department` (
  `id` int(2) NOT NULL,
  `title` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `user_id_created` bigint(255) DEFAULT NULL,
  `user_id_updated` bigint(255) DEFAULT NULL,
  `status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Despejando dados para a tabela `tbsys_ticket_department`
--

INSERT INTO `tbsys_ticket_department` (`id`, `title`, `created_at`, `updated_at`, `user_id_created`, `user_id_updated`, `status`) VALUES
(1, 'Financeiro', '2025-08-22 22:16:32', '2025-08-22 22:43:50', 2, 2, 1),
(2, 'Suporte Geral', '2025-08-14 22:16:28', '2025-08-23 11:12:10', 2, 2, 1),
(3, 'Dúvidas', '2025-08-22 22:38:10', '2025-12-30 12:15:22', 2, 2, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbsys_ticket_department_subdepartment`
--

CREATE TABLE `tbsys_ticket_department_subdepartment` (
  `id` int(3) NOT NULL,
  `ticket_department_id` int(2) NOT NULL,
  `title` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `status` int(1) NOT NULL,
  `ticket_department_subdepartment_priority_id` int(2) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `user_id_created` bigint(255) DEFAULT NULL,
  `user_id_updated` bigint(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Despejando dados para a tabela `tbsys_ticket_department_subdepartment`
--

INSERT INTO `tbsys_ticket_department_subdepartment` (`id`, `ticket_department_id`, `title`, `status`, `ticket_department_subdepartment_priority_id`, `created_at`, `updated_at`, `user_id_created`, `user_id_updated`) VALUES
(1, 2, 'Email', 1, 3, NULL, NULL, NULL, NULL),
(2, 2, 'Acesso', 1, 1, NULL, NULL, NULL, NULL),
(3, 2, 'Login', 1, 5, NULL, '2025-08-23 11:10:41', NULL, 2),
(4, 1, 'Pagamento', 1, 5, NULL, NULL, NULL, NULL),
(5, 2, 'Indisponibilidade', 1, 5, '2025-08-23 11:11:35', NULL, 2, NULL),
(6, 1, 'Site Bloqueado', 1, 5, '2025-08-23 11:12:50', NULL, 2, NULL),
(7, 3, 'Cupom de desconto', 1, 1, '2025-08-23 11:13:38', '2025-12-30 12:15:26', 2, 2);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbsys_ticket_department_subdepartment_agent`
--

CREATE TABLE `tbsys_ticket_department_subdepartment_agent` (
  `id` bigint(255) NOT NULL,
  `ticket_department_subdepartment_id` int(3) NOT NULL,
  `ticket_agent_id` bigint(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Despejando dados para a tabela `tbsys_ticket_department_subdepartment_agent`
--

INSERT INTO `tbsys_ticket_department_subdepartment_agent` (`id`, `ticket_department_subdepartment_id`, `ticket_agent_id`) VALUES
(142, 4, 2),
(143, 6, 2),
(144, 1, 2),
(145, 2, 2),
(146, 3, 2),
(147, 5, 2),
(148, 7, 2),
(149, 4, 1),
(150, 1, 1),
(151, 2, 1),
(152, 3, 1),
(159, 4, 5),
(160, 6, 5),
(161, 3, 5),
(162, 7, 5);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbsys_ticket_department_subdepartment_priority`
--

CREATE TABLE `tbsys_ticket_department_subdepartment_priority` (
  `id` int(2) NOT NULL,
  `level` int(1) NOT NULL,
  `title` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `deadline` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Despejando dados para a tabela `tbsys_ticket_department_subdepartment_priority`
--

INSERT INTO `tbsys_ticket_department_subdepartment_priority` (`id`, `level`, `title`, `deadline`) VALUES
(1, 1, 'Baixo', 48),
(2, 2, 'Normal', 36),
(3, 3, 'Urgente', 24),
(4, 4, 'Muito Urgente', 18),
(5, 5, 'Emergência', 12);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbsys_ticket_send`
--

CREATE TABLE `tbsys_ticket_send` (
  `id` bigint(255) NOT NULL,
  `ticket_id` bigint(255) DEFAULT NULL,
  `user_id` bigint(255) DEFAULT 0,
  `customer_id` bigint(255) DEFAULT 0,
  `message` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `file` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `date_send` datetime DEFAULT NULL,
  `date_read` datetime DEFAULT NULL,
  `status` int(1) DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Despejando dados para a tabela `tbsys_ticket_send`
--

INSERT INTO `tbsys_ticket_send` (`id`, `ticket_id`, `user_id`, `customer_id`, `message`, `file`, `date_send`, `date_read`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 1, 'mensagem cliente teste', NULL, '2025-08-25 13:57:29', '2025-08-26 15:11:21', 1, NULL, '2025-08-26 15:11:21'),
(2, 1, 1, NULL, 'mensagem user teste', NULL, '2025-08-25 13:57:29', NULL, 2, NULL, '2025-12-31 12:35:24'),
(4, 1, 2, 0, '<p>enviando mensagem de teste</p>', NULL, '2025-08-25 15:21:00', NULL, 1, '2025-08-25 15:21:42', NULL),
(23, 1, 2, 0, 'teste', '01.png', '2025-09-13 10:19:00', NULL, 1, '2025-09-13 10:19:22', NULL),
(26, 1, 2, 0, 'teste', 'images_1.jpg', '2025-12-31 12:44:00', NULL, 1, '2025-12-31 12:44:16', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbsys_ticket_send_file`
--

CREATE TABLE `tbsys_ticket_send_file` (
  `id` bigint(255) NOT NULL,
  `ticket_send_id` bigint(255) NOT NULL,
  `file` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `date_register` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbsys_users`
--

CREATE TABLE `tbsys_users` (
  `id` bigint(255) NOT NULL,
  `department_id` int(5) DEFAULT NULL,
  `department_occupation_id` int(15) DEFAULT NULL,
  `name` varchar(75) NOT NULL,
  `cpf` varchar(11) NOT NULL,
  `photo` text DEFAULT NULL,
  `wallpaper` text DEFAULT NULL,
  `birth` date NOT NULL,
  `contact` varchar(20) NOT NULL,
  `gcid` char(36) NOT NULL,
  `privilege_id` int(2) NOT NULL,
  `administrative` int(1) NOT NULL,
  `email` varchar(120) NOT NULL,
  `passwd` text NOT NULL,
  `salt` text NOT NULL,
  `token` text DEFAULT NULL,
  `token_date` datetime DEFAULT NULL,
  `code` text DEFAULT NULL,
  `status` int(1) NOT NULL,
  `status_agent` int(1) DEFAULT 0,
  `session_date` datetime DEFAULT NULL,
  `session_date_last` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `language_id` int(3) NOT NULL,
  `currency_id` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Despejando dados para a tabela `tbsys_users`
--

INSERT INTO `tbsys_users` (`id`, `department_id`, `department_occupation_id`, `name`, `cpf`, `photo`, `wallpaper`, `birth`, `contact`, `gcid`, `privilege_id`, `administrative`, `email`, `passwd`, `salt`, `token`, `token_date`, `code`, `status`, `status_agent`, `session_date`, `session_date_last`, `created_at`, `updated_at`, `language_id`, `currency_id`) VALUES
(1, 1, 1, 'Ricardo Gomes', '03456150504', 'img2.jpg', 'wallpaper.png', '1991-02-19', '71999903997', '659e0f49-1cb1-1992-2551-1d662957e3', 1, 1, 'ricardo22ssa@gmail.com', '8494178e69a86d22904effd8aa946ccf03a52fd5578a3d05dd707c9e5b5c04e1d2e2623b6a27632880f5d068044e5d74a4c869b1b7c0a485f715589f308ea634', '43cc0c17e26c8fbb9695c130cf9418d7c398dfe033c845e5bdfea21b66d70a943a3dd9536545755bf287dbbc0f1ea69c52fce7e53ad0b51a05b7f2ef', '', '0000-00-00 00:00:00', '', 1, 1, '2025-11-13 23:06:53', '2025-11-13 23:06:53', '0000-00-00 00:00:00', '2026-01-25 01:07:39', 1, 1),
(2, 2, 2, 'Andre Ricardo', '82074404069', 'images_1.jpg', NULL, '1991-02-18', '(71) 99990-3997', '7210f702-2f12-46a9-bd2d-e2ecc9da9e78', 1, 1, 'ricardogssa@gmail.com', 'c90e1e30511c96607724d3118650491071b81429e3338e9f001b4df9a78d55b3e672e6ddef549fb8968cab9ec819c3363313367165cf0960b3addef8fe987dbc', '1266761ad5303d5ebdefe167848a7324dfef2af8dbba448ec5bd543c5ddf13bbf30712f300287680eb44a3a35cb08e2e1d867ac55b1669a22e634d16b5295edb', NULL, NULL, NULL, 1, 1, '2026-02-09 17:28:21', '2026-02-09 17:28:21', '2025-03-04 15:44:29', '2026-02-09 17:28:21', 1, 1),
(5, 5, 7, 'Andre Ricardo', '04426754704', NULL, NULL, '1991-02-18', '71999903997', 'c67dd301-59f8-4d8f-8c12-87f57705713b', 1, 1, 'subjimmy000@gmail.com', '703960a9a044787efaa465d3265088ccdd035826902b7405e10bab363175838114aac1ee330ed2c927baef55484cb38bc3d7061097ddb761e748ec78e06bd219', '5eccf192e6e9b4b17aff193d9f97d4ec9671498d344e51da05e58abf933c690427683e9892c878b90124bcbc102db074be28b5f18b1edd004f2eddd13ef2e0ff', NULL, NULL, NULL, 1, 1, '2025-11-14 22:08:56', '2025-11-14 22:08:56', '2025-11-14 22:07:46', '2025-12-30 14:23:57', 1, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbsys_videos_metrics`
--

CREATE TABLE `tbsys_videos_metrics` (
  `id` bigint(255) NOT NULL,
  `video_id` varchar(25) DEFAULT NULL,
  `view_count` bigint(255) DEFAULT NULL,
  `like_count` bigint(255) DEFAULT NULL,
  `comment_count` int(11) DEFAULT NULL,
  `fetched_at` timestamp NULL DEFAULT current_timestamp(),
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `user_id_created` bigint(255) DEFAULT NULL,
  `user_id_updated` bigint(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbsys_videos_scripts`
--

CREATE TABLE `tbsys_videos_scripts` (
  `id` bigint(255) NOT NULL,
  `gcid` text NOT NULL,
  `customer_id` text NOT NULL,
  `channel_gcid` text DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `keywords` text NOT NULL,
  `content` text NOT NULL,
  `hashtags` text DEFAULT NULL,
  `description_video` text DEFAULT NULL,
  `video_length` int(11) DEFAULT NULL,
  `post_date` date NOT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `status_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Despejando dados para a tabela `tbsys_videos_scripts`
--

INSERT INTO `tbsys_videos_scripts` (`id`, `gcid`, `customer_id`, `channel_gcid`, `title`, `keywords`, `content`, `hashtags`, `description_video`, `video_length`, `post_date`, `thumbnail`, `created_at`, `updated_at`, `status_id`) VALUES
(9, '4ce3be97-3de4-438b-9974-91ed97a4a41c', '7192e5a9-f495-4951-89a1-ff5c285256f1', 'aaa6c22d-3e2a-420d-8a08-d98b729dfdb2', 'teste titulo', 'aqui, as, palavras, chaves', '<p>teste roteiro</p>', 'teste hash', 'teste desc', 8, '2025-11-30', 'scripts_4397344389974919744120251130115024.png', '2025-11-29 23:31:14', '2025-12-06 21:58:55', 6),
(11, '0cc831c3-1149-4767-81d2-955c2fca991c', '7192e5a9-f495-4951-89a1-ff5c285256f1', '27bb28cd-d53b-4f8f-b0b4-44bb8da4ef24', 'A quanto tempo, Operadora Um! 86 Eighty Six Dublado', '86 eighty six, eighty six, eightysix, 86 eighty six dublado', '<p>texto</p>', '#86EightySixDublado #AnimeDublado #86EightySix', 'Prepare-se para reviver a emoção de 86 Eighty Six Dublado! Neste vídeo, você vai conferir o reencontro épico e aguardado da Operadora Um em português, com toda a qualidade que você merece. Uma experiência imperdível para os fãs de 86 Eighty Six dublado que querem sentir cada momento com a voz dos nossos talentosos dubladores.\r\n\r\nReviva um dos momentos mais marcantes de 86 Eighty Six com a Operadora Um de volta! Explore a emoção do reencontro e sinta toda a profundidade da trama com a excelente dublagem em português. Perfeito para quem ama o anime e quer mergulhar ainda mais na história e na intensidade dos personagens.\r\n\r\nNão perca mais nenhum momento! Inscreva-se no canal para mais conteúdos incríveis de animes e deixe seu comentário sobre o que achou dessa cena épica!\r\n\r\n#86EightySixDublado #AnimeDublado #86EightySix', 10, '2025-12-11', 'scripts_0831311494767812955299120251203130318.png', '2025-12-03 13:03:18', '2026-01-04 17:34:19', 4),
(12, 'd85df370-3f19-418d-a5a3-28b286f09f05', '7192e5a9-f495-4951-89a1-ff5c285256f1', '27bb28cd-d53b-4f8f-b0b4-44bb8da4ef24', 'A quanto tempo Operadora Um! 86 eighty six', 'eightysix, eighty six, 86 eightysix', '<div class=\"scene-block\">\r\n<p>\"</p>\r\n<h2>86 EIGHTY-SIX: H&aacute; Quanto Tempo, Operadora Um? A Frase Que Mudou TUDO!</h2>\r\n<p><em>Tempo estimado: 12 min</em></p>\r\n<h3>00:00 - HOOK: A Frase Inesquec&iacute;vel de 86 EIGHTY-SIX</h3>\r\n<p><strong>(Visual):</strong> Cena ic&ocirc;nica de Shin e Lena se reencontrando no campo de flores Lycoris. Close no rosto de Shin, depois Lena. Transi&ccedil;&atilde;o r&aacute;pida para cenas de a&ccedil;&atilde;o e desespero do anime 86 Eighty-Six.</p>\r\n<p><strong>(&Aacute;udio):</strong> M&uacute;sica tensa de 86 (ex: \\\"Avid\\\"). Voz do narrador empolgada: \\\"Voc&ecirc; se lembra daquele momento? Aquele arrepio na espinha quando Shin finalmente diz: <strong>\'H&aacute; quanto tempo, Operadora Um.\'</strong> Pra muitos, essa n&atilde;o foi s&oacute; uma frase, foi um terremoto emocional! Mas por que esse reencontro em <strong>86 Eighty-Six</strong> &eacute; t&atilde;o poderoso? O que fez essa simples sauda&ccedil;&atilde;o ressoar t&atilde;o fundo nos nossos cora&ccedil;&otilde;es? Se voc&ecirc; &eacute; f&atilde; de <strong>eightysix</strong>, prepare-se, porque vamos mergulhar na hist&oacute;ria por tr&aacute;s dessa cena &eacute;pica!\\\"</p>\r\n<hr>\r\n<h3>00:45 - INTRODU&Ccedil;&Atilde;O: O PESO DE UM REENCONTRO</h3>\r\n<p><strong>(Visual):</strong> Montagem r&aacute;pida de momentos chave de Shin e Lena, com foco na dist&acirc;ncia entre eles. Legendas din&acirc;micas com \\\"Dist&acirc;ncia\\\", \\\"Conex&atilde;o\\\", \\\"Espera\\\".</p>\r\n<p><strong>(&Aacute;udio):</strong> Narrador: \\\"Galera, <strong>86 Eighty-Six</strong> n&atilde;o &eacute; s&oacute; sobre mechas e guerra. &Eacute; sobre a alma humana, a dor da separa&ccedil;&atilde;o e a esperan&ccedil;a de um reencontro. A frase de Shin para Lena n&atilde;o &eacute; um mero \'ol&aacute;\'. &Eacute; o ponto final de um ciclo de dor, dist&acirc;ncia e sacrif&iacute;cio. &Eacute; a recompensa por tudo que eles viveram e sofreram. E hoje, vamos desvendar cada camada que tornou esse momento um dos mais ic&ocirc;nicos do anime recente! Prepare-se para sentir de novo!\\\"</p>\r\n<hr>\r\n<h3>01:30 - CONTEXTO: A DIST&Acirc;NCIA INVIS&Iacute;VEL DO PARA-RAID</h3>\r\n<p><strong>(Visual):</strong> Gr&aacute;ficos animados explicando o sistema Para-RAID. Cenas de Shin e seu esquadr&atilde;o em combate no Setor 86, e Lena em sua sala de controle na Rep&uacute;blica. Linhas tracejadas conectando-os, mas com uma barreira no meio.</p>\r\n<p><strong>(&Aacute;udio):</strong> Narrador: \\\"Pra entender a for&ccedil;a desse \'H&aacute; quanto tempo\', precisamos voltar ao in&iacute;cio da conex&atilde;o deles. Shin e Lena, ou melhor, o Esquadr&atilde;o Spearhead e a Operadora Um, estavam ligados por uma tecnologia insana: o Para-RAID. Eles <strong>ouviam</strong> um ao outro, sentiam as emo&ccedil;&otilde;es, mas estavam separados por uma barreira intranspon&iacute;vel: a ideologia podre da Rep&uacute;blica e a linha de frente do Setor 86. Era uma conex&atilde;o &iacute;ntima, mas frustrante. Quantas vezes a gente n&atilde;o quis gritar pra eles se encontrarem logo? Essa dist&acirc;ncia, essa barreira f&iacute;sica e ideol&oacute;gica, foi a base que construiu a tens&atilde;o para o reencontro. A gente sentia a dor da separa&ccedil;&atilde;o junto com eles!\\\"</p>\r\n<hr>\r\n<h3>04:00 - AN&Aacute;LISE PROFUNDA: A JORNADA AT&Eacute; O REENCONTRO</h3>\r\n<h4>04:15 - A EVOLU&Ccedil;&Atilde;O DE LENA: DE INOCENTE A BLOODY REINA</h4>\r\n<p><strong>(Visual):</strong> Montagem da evolu&ccedil;&atilde;o de Lena: de uma jovem idealista e ing&ecirc;nua para a \\\"Bloody Reina\\\" determinada. Cenas dela tomando decis&otilde;es dif&iacute;ceis, lutando contra o sistema, e correndo para alcan&ccedil;ar Shin. Um gr&aacute;fico mostrando o \\\"n&iacute;vel de badass\\\" de Lena subindo.</p>\r\n<p><strong>(&Aacute;udio):</strong> Narrador: \\\"Mas o reencontro s&oacute; tem esse peso porque a Lena que Shin encontra n&atilde;o &eacute; a mesma Operadora Um do in&iacute;cio. Ela evoluiu, e MUITO! De uma idealista ing&ecirc;nua que achava que podia mudar tudo com palavras, ela se tornou a <strong>Bloody Reina</strong>. Ela viu a realidade cruel de <strong>86 Eighty-Six</strong>, ela lutou, ela correu, ela desobedeceu ordens, ela se sacrificou para alcan&ccedil;ar os 86. Ela n&atilde;o esperou Shin; ela foi atr&aacute;s dele. Essa jornada de Lena, de uma garota protegida para uma l&iacute;der respeitada, &eacute; o que d&aacute; credibilidade e emo&ccedil;&atilde;o ao momento. Ela <strong>ganhou</strong> o direito de estar ali, de ser chamada de \'Operadora Um\' de novo. Ela conquistou o respeito de Shin, e o nosso tamb&eacute;m!\\\"</p>\r\n<h4>06:30 - O TRAUMA DE SHIN: UM PROP&Oacute;SITO AL&Eacute;M DA MORTE</h4>\r\n<p><strong>(Visual):</strong> Cenas de Shin carregando o peso dos mortos, sua express&atilde;o vazia em combate. Flashbacks de seus irm&atilde;os mortos. O momento em que ele descobre que Lena est&aacute; viva, e um sorriso sutil surge. Cenas de Shin lutando com um novo prop&oacute;sito.</p>\r\n<p><strong>(&Aacute;udio):</strong> Narrador: \\\"E do lado de Shin? Ah, o Shin... Ele carregava o peso de centenas de mortos, a s&iacute;ndroma do sobrevivente em sua forma mais brutal. Ele era uma arma, um ceifador que s&oacute; via o fim da linha. O amanh&atilde; n&atilde;o existia para ele, apenas a miss&atilde;o de guiar as almas perdidas. Mas a descoberta de que Lena estava viva, que a Operadora Um ainda estava l&aacute;, deu a ele algo que ele tinha perdido: um <strong>prop&oacute;sito</strong>. N&atilde;o apenas lutar, mas <strong>viver</strong>. Ele tinha algu&eacute;m para reencontrar, algu&eacute;m que o via al&eacute;m de uma m&aacute;quina de matar. Essa esperan&ccedil;a, essa fa&iacute;sca de um futuro, tirou Shin do vazio e o impulsionou para frente. &Eacute; por isso que a frase dele &eacute; t&atilde;o carregada: &eacute; a confirma&ccedil;&atilde;o de que ele encontrou o seu \'amanh&atilde;\'.\\\"</p>\r\n<hr>\r\n<h3>09:00 - CURIOSIDADES: DETALHES ESCONDIDOS DA CENA</h3>\r\n<p><strong>(Visual):</strong> Cena do campo de Lycoris em c&acirc;mera lenta, com anota&ccedil;&otilde;es visuais destacando detalhes. Imagens do mang&aacute; e light novel mostrando a cena. Curiosidades em texto na tela.</p>\r\n<p><strong>(&Aacute;udio):</strong> Narrador: \\\"Agora, segura essa! Voc&ecirc; sabia que a cena do reencontro no campo de flores Lycoris &eacute; uma obra de arte em si? A dire&ccedil;&atilde;o de arte &eacute; impec&aacute;vel, com o sil&ecirc;ncio quebrado apenas pelo vento, amplificando a emo&ccedil;&atilde;o. As flores Lycoris, ou \'l&iacute;rios-aranha\', no Jap&atilde;o, s&atilde;o frequentemente associadas &agrave; morte, ao reencontro no p&oacute;s-vida, ou &agrave; separa&ccedil;&atilde;o. Mas aqui em <strong>86 Eighty-Six</strong>, elas simbolizam um reencontro al&eacute;m da morte, um novo come&ccedil;o! E o fato de Shin cham&aacute;-la de \'Handler One\' e n&atilde;o \'Lena\'? Isso valida tudo o que eles passaram, a conex&atilde;o &uacute;nica que desenvolveram no campo de batalha. &Eacute; um reconhecimento do passado que finalmente elimina a \'est&aacute;tica\' entre eles. &Eacute; como se ele dissesse: \'Eu te reconhe&ccedil;o, eu te vejo, eu te esperei\'.\\\"</p>\r\n<ul>\r\n<li><strong>Curiosidade 1:</strong> As flores Lycoris s&atilde;o um s&iacute;mbolo forte na cultura japonesa, muitas vezes associadas &agrave; passagem para o outro mundo.</li>\r\n<li><strong>Curiosidade 2:</strong> O anime fez um trabalho excepcional em adaptar essa cena do light novel, adicionando camadas visuais e sonoras que a tornaram ainda mais impactante.</li>\r\n<li><strong>Curiosidade 3:</strong> A escolha de Shin em usar \'Handler One\' em vez de \'Lena\' mostra que, para ele, a conex&atilde;o de batalha foi o que os uniu e a base para o futuro.</li>\r\n</ul>\r\n<hr>\r\n<h3>10:30 - FECHAMENTO: A CONEX&Atilde;O HUMANA EM MEIO &Agrave; GUERRA</h3>\r\n<p><strong>(Visual):</strong> Cenas emocionantes de 86, focando na conex&atilde;o entre os personagens. Transi&ccedil;&atilde;o para a tela final do canal.</p>\r\n<p><strong>(&Aacute;udio):</strong> Narrador: \\\"No fim das contas, a frase \'H&aacute; quanto tempo, Operadora Um\' em <strong>86 Eighty-Six</strong> n&atilde;o &eacute; apenas um di&aacute;logo, &eacute; a alma da obra. &Eacute; a prova de que, mesmo no inferno da guerra, a conex&atilde;o humana, a esperan&ccedil;a e o amor podem florescer. &Eacute; a recompensa por uma jornada de dor, sacrif&iacute;cio e supera&ccedil;&atilde;o. Esse momento nos lembra por que amamos tanto animes como <strong>eighty six</strong>!\\\"</p>\r\n<hr>\r\n<h3>11:30 - CALL TO ACTION (CTA)</h3>\r\n<p><strong>(Visual):</strong> Tela final do canal com bot&otilde;es de \\\"Inscrever-se\\\", \\\"Curtir\\\" e \\\"Comentar\\\". Imagem de preview de um pr&oacute;ximo v&iacute;deo de teoria.</p>\r\n<p><strong>(&Aacute;udio):</strong> Narrador: \\\"E a&iacute;, o que voc&ecirc; sentiu quando Shin disse essa frase? Qual foi o seu momento mais emocionante em <strong>86 Eighty-Six</strong>? Deixa seu coment&aacute;rio aqui embaixo, quero muito saber a sua opini&atilde;o! Se voc&ecirc; curtiu essa an&aacute;lise profunda, n&atilde;o esquece de deixar o like e <strong>se inscrever no canal</strong> para n&atilde;o perder os pr&oacute;ximos v&iacute;deos! Temos muitas teorias e an&aacute;lises de animes vindo por a&iacute;, incluindo mais de <strong>86 eightysix</strong>! At&eacute; a pr&oacute;xima, otaku!\\\"</p>\r\n\"\r\n<p>&nbsp;</p>\r\n</div>\r\n<hr>', '#86EightySix #Anime #OperadoraUm', 'Quer reviver os momentos mais emocionantes de 86 eighty six? Prepare-se para uma viagem nostálgica e profunda pelo universo de eighty six!\r\n\r\nNeste vídeo especial, mergulhamos na complexa relação entre o Ceifador e a Operadora Um, desvendando os laços inquebráveis formados no campo de batalha. Relembre os sacrifícios, as esperanças e a luta incessante pela liberdade que definem o incrível mundo de 86. Uma homenagem emocionante para todos os fãs!\r\n\r\nGostou? Deixe seu like, inscreva-se no canal para mais conteúdo de animes e compartilhe suas memórias de 86 nos comentários!\r\n\r\n#86EightySix #Anime #OperadoraUm', 12, '2025-12-08', 'scripts_853703194185328286090520251204211215.png', '2025-12-04 21:12:15', '2025-12-28 17:34:20', 8),
(13, '9fb6a18a-1ae8-4b1b-b0f4-946c1e6cef9d', '7192e5a9-f495-4951-89a1-ff5c285256f1', '27bb28cd-d53b-4f8f-b0b4-44bb8da4ef24', 'A quanto tempo, Operadora Um! 86 Eighty Six Dublado', '86 eighty six, eighty six, eightysix, 86 eighty six dublado', '<div class=\"scene-block\">\r\n<p>\"</p>\r\n<h2>A quanto tempo, Operadora Um! O ENCONTRO &Eacute;PICO de 86 Eighty-Six Dublado que NINGU&Eacute;M ESPERAVA!</h2>\r\n\\n\r\n<p><em>Tempo estimado: 10 min</em></p>\r\n\\n\\n\r\n<h3>00:00 - HOOK</h3>\r\n\\n\r\n<p><strong>(Visual):</strong> Cena ic&ocirc;nica do reencontro de Shin e Lena no campo de Lycoris. Close nos rostos emocionados, c&acirc;mera lenta.</p>\r\n\\n\r\n<p><strong>(&Aacute;udio):</strong> Trilha sonora emocionante de 86 Eighty-Six. Voz do narrador empolgada.</p>\r\n\\n\r\n<p>Fala, galera otaku! Voc&ecirc; j&aacute; sentiu aquela pontada no cora&ccedil;&atilde;o quando dois personagens que voc&ecirc; torce MUITO finalmente se encontram? Em <strong>86 Eighty-Six</strong>, esse momento &eacute; mais que um \\\"oi\\\", &eacute; um grito de al&iacute;vio que ecoa por toda a guerra! Mas por que a frase \\\"H&aacute; quanto tempo, Operadora Um!\\\" &eacute; t&atilde;o poderosa? Vem comigo que a gente vai desvendar esse mist&eacute;rio!</p>\r\n\\n<hr>\\n\\n\r\n<h3>00:45 - INTRODU&Ccedil;&Atilde;O: O PESO DE UMA FRASE</h3>\r\n\\n\r\n<p><strong>(Visual):</strong> Montagem r&aacute;pida de cenas de a&ccedil;&atilde;o e drama de 86 Eighty-Six, focando na separa&ccedil;&atilde;o e nos momentos de conex&atilde;o via Para-RAID.</p>\r\n\\n\r\n<p><strong>(&Aacute;udio):</strong> Trilha sonora crescendo, narrador com tom mais s&eacute;rio, mas ainda empolgado.</p>\r\n\\n\r\n<p>Sejam bem-vindos ao canal onde a gente mergulha de cabe&ccedil;a nos animes mais insanos! E hoje, vamos desvendar por que uma simples frase em <strong>86 Eighty-Six</strong>, \\\"H&aacute; quanto tempo, Operadora Um!\\\", se tornou um dos momentos mais arrepiantes e emocionantes da anima&ccedil;&atilde;o recente. Prepare-se, porque a gente vai al&eacute;m da a&ccedil;&atilde;o dos Juggernauts para sentir a dor, a esperan&ccedil;a e a conex&atilde;o que constru&iacute;ram esse reencontro &eacute;pico no universo de <strong>eighty six</strong>!</p>\r\n\\n<hr>\\n\\n\r\n<h3>01:30 - CONTEXTO: A DIST&Acirc;NCIA E O PARA-RAID</h3>\r\n\\n\r\n<p><strong>(Visual):</strong> Gr&aacute;ficos explicativos do sistema Para-RAID. Cenas de Shin e Lena se comunicando atrav&eacute;s da est&aacute;tica, mostrando a barreira f&iacute;sica e emocional.</p>\r\n\\n\r\n<p><strong>(&Aacute;udio):</strong> Efeitos sonoros de est&aacute;tica, voz do narrador explicando de forma clara e r&aacute;pida.</p>\r\n\\n\r\n<p>Pra entender a for&ccedil;a desse momento, a gente precisa voltar um pouco. Em <strong>86 Eighty-Six</strong>, a din&acirc;mica entre Shin e Lena &eacute; &uacute;nica: eles est&atilde;o conectados apenas pela voz, atrav&eacute;s do sistema Para-RAID. &Eacute; tipo um Discord interdimensional, mas com muito mais drama e perigo real! Enquanto Lena est&aacute; segura na Rep&uacute;blica de San Magnolia, Shin e os Oitenta e Seis est&atilde;o na linha de frente, lutando uma guerra sem fim. Uma barreira f&iacute;sica, ideol&oacute;gica e, acima de tudo, emocional os separava. A gente, como f&atilde;, s&oacute; queria ver essa parede cair, n&eacute;? E a cada epis&oacute;dio, a tens&atilde;o s&oacute; aumentava!</p>\r\n\\n<hr>\\n\\n\r\n<h3>03:00 - AN&Aacute;LISE 1: A EVOLU&Ccedil;&Atilde;O DE LENA, A BLOODY REINA</h3>\r\n\\n\r\n<p><strong>(Visual):</strong> Montagem da evolu&ccedil;&atilde;o de Lena: de oficial ing&ecirc;nua e idealista para a \\\"Bloody Reina\\\" determinada. Cenas dela tomando decis&otilde;es dif&iacute;ceis e lutando por seus ideais.</p>\r\n\\n\r\n<p><strong>(&Aacute;udio):</strong> M&uacute;sica mais intensa, narrador destacando a for&ccedil;a da personagem.</p>\r\n\\n\r\n<p>Mas esse reencontro s&oacute; tem peso por causa da jornada da Lena. No in&iacute;cio, ela era aquela oficial cheia de ideais, mas um pouco ing&ecirc;nua, sabe? Mas a conviv&ecirc;ncia (mesmo que &agrave; dist&acirc;ncia) com os Oitenta e Seis, e a dura realidade da guerra, a transformaram na lend&aacute;ria \\\"Bloody Reina\\\"! Ela n&atilde;o ficou parada, ela correu, lutou e se sacrificou pra tentar alcan&ccedil;&aacute;-los. Ela n&atilde;o s&oacute; ganhou o nosso respeito, mas, o mais importante, ela conquistou o respeito do pr&oacute;prio Shin. Sem essa evolu&ccedil;&atilde;o, o \\\"Ol&aacute;\\\" seria s&oacute; um \\\"Ol&aacute;\\\". Com ela, &eacute; a valida&ccedil;&atilde;o de todo um arco de sacrif&iacute;cio!</p>\r\n\\n<hr>\\n\\n\r\n<h3>05:00 - AN&Aacute;LISE 2: O TRAUMA DE SHIN E A ESPERAN&Ccedil;A</h3>\r\n\\n\r\n<p><strong>(Visual):</strong> Cenas de Shin carregando o peso dos mortos, sua express&atilde;o vazia. Depois, cenas mostrando sua nova determina&ccedil;&atilde;o e a busca por Lena.</p>\r\n\\n\r\n<p><strong>(&Aacute;udio):</strong> Voz do narrador mais reflexiva, com momentos de esperan&ccedil;a na entona&ccedil;&atilde;o.</p>\r\n\\n\r\n<p>E o Shin? Ah, o Shin... Ele carregava a s&iacute;ndrome do sobrevivente, um vazio existencial onde ele era apenas uma arma, um ceifador de almas. A miss&atilde;o dele era levar os seus companheiros mortos para casa. Mas a descoberta de que Lena estava viva, que ela n&atilde;o os abandonou, deu a ele um novo prop&oacute;sito. De repente, n&atilde;o era s&oacute; sobre o passado e os mortos, era sobre \\\"o amanh&atilde;\\\", sobre um futuro que ele podia construir. Essa esperan&ccedil;a, essa fa&iacute;sca, foi o que o tirou do abismo e o impulsionou para frente. &Eacute; a prova de que mesmo em um mundo brutal como o de <strong>eightysix</strong>, a conex&atilde;o humana pode ser a luz no fim do t&uacute;nel.</p>\r\n\\n<hr>\\n\\n\r\n<h3>07:00 - AN&Aacute;LISE 3: O CL&Iacute;MAX VISUAL - O CAMPO DE LYCORIS</h3>\r\n\\n\r\n<p><strong>(Visual):</strong> A cena do reencontro em c&acirc;mera lenta, focando nos detalhes: o campo de flores Lycoris vermelhas, o sil&ecirc;ncio, a express&atilde;o de Shin e Lena. Close nos olhos.</p>\r\n\\n\r\n<p><strong>(&Aacute;udio):</strong> Trilha sonora suave e emotiva, depois o sil&ecirc;ncio, e ent&atilde;o a voz de Shin. Narrador com tom quase reverente.</p>\r\n\\n\r\n<p>E chegamos ao &aacute;pice! A cena no campo de flores Lycoris &eacute; pura poesia visual. A dire&ccedil;&atilde;o de arte &eacute; impec&aacute;vel, com o vermelho vibrante das flores contrastando com a brutalidade da guerra que eles viveram. O sil&ecirc;ncio antes da frase, a forma como Shin se vira e, finalmente, a voz dele pronunciando: \\\"H&aacute; quanto tempo, Operadora Um!\\\". N&atilde;o &eacute; \\\"Lena\\\", n&atilde;o &eacute; \\\"Vladilena\\\", &eacute; \\\"Operadora Um\\\". Isso valida todo o passado deles, toda a dor, toda a dist&acirc;ncia, e mostra que a conex&atilde;o deles transcendeu tudo. &Eacute; como se toda a \\\"est&aacute;tica\\\" do Para-RAID finalmente desaparecesse, e eles estivessem ali, reais, um para o outro. &Eacute; um momento que faz a gente querer rever <strong>86 eighty six dublado</strong> s&oacute; pra sentir de novo essa emo&ccedil;&atilde;o!</p>\r\n\\n\r\n<ul>\r\n<li>\\n</li>\r\n<li><strong>Curiosidade Surpresa:</strong> As flores Lycoris, tamb&eacute;m conhecidas como \\\"Flores da Morte\\\" ou \\\"L&iacute;rios da Aranha Vermelha\\\", s&atilde;o frequentemente associadas a mem&oacute;rias perdidas, reencontros e o mundo dos mortos na cultura japonesa. Perfeito para a tem&aacute;tica de Shin!</li>\r\n<li>\\n</li>\r\n</ul>\r\n\\n<hr>\\n\\n\r\n<h3>09:00 - FECHAMENTO E CTA</h3>\r\n\\n\r\n<p><strong>(Visual):</strong> Montagem de cenas emocionantes de 86 Eighty-Six, com foco na conex&atilde;o dos personagens. Logo do canal.</p>\r\n\\n\r\n<p><strong>(&Aacute;udio):</strong> Trilha sonora final, voz do narrador entusiasmada.</p>\r\n\\n\r\n<p>Ent&atilde;o, galera, a frase \\\"H&aacute; quanto tempo, Operadora Um!\\\" em <strong>86 Eighty-Six</strong> &eacute; muito mais do que um simples cumprimento. &Eacute; a recompensa emocional de uma jornada brutal, a prova de que a conex&atilde;o humana pode florescer mesmo no campo de batalha mais desolador. &Eacute; por isso que <strong>eighty six</strong> &eacute; um anime que a gente precisa discutir e celebrar!</p>\r\n\\n\r\n<p>E a&iacute;, qual foi a cena que mais te emocionou em <strong>86 Eighty-Six</strong>? Deixa seu coment&aacute;rio aqui embaixo! E se voc&ecirc; curtiu essa an&aacute;lise, n&atilde;o esquece de deixar o like, se inscrever no canal pra n&atilde;o perder as pr&oacute;ximas teorias e an&aacute;lises insanas de animes, e ativar o sininho! A gente se v&ecirc; no pr&oacute;ximo v&iacute;deo, otaku!</p>\r\n\\n<hr>\"\r\n<p>&nbsp;</p>\r\n</div>\r\n<hr>', '#86EightySixDublado #AnimeDublado #86EightySix', 'Prepare-se para reviver a emoção de 86 Eighty Six Dublado! Neste vídeo, você vai conferir o reencontro épico e aguardado da Operadora Um em português, com toda a qualidade que você merece. Uma experiência imperdível para os fãs de 86 Eighty Six dublado que querem sentir cada momento com a voz dos nossos talentosos dubladores.\r\n\r\nReviva um dos momentos mais marcantes de 86 Eighty Six com a Operadora Um de volta! Explore a emoção do reencontro e sinta toda a profundidade da trama com a excelente dublagem em português. Perfeito para quem ama o anime e quer mergulhar ainda mais na história e na intensidade dos personagens.\r\n\r\nNão perca mais nenhum momento! Inscreva-se no canal para mais conteúdos incríveis de animes e deixe seu comentário sobre o que achou dessa cena épica!\r\n\r\n#86EightySixDublado #AnimeDublado #86EightySix', 12, '2026-01-29', 'scripts_961818410494616920260102213903.png', '2025-12-05 09:26:10', '2026-01-04 17:25:51', 6);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbsys_videos_scripts_status`
--

CREATE TABLE `tbsys_videos_scripts_status` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(50) NOT NULL,
  `color` varchar(20) DEFAULT NULL,
  `backgroundColor` varchar(30) DEFAULT NULL,
  `borderColor` varchar(30) DEFAULT NULL,
  `textColor` varchar(30) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `user_id_created` bigint(255) DEFAULT NULL,
  `user_id_updated` bigint(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tbsys_videos_scripts_status`
--

INSERT INTO `tbsys_videos_scripts_status` (`id`, `title`, `color`, `backgroundColor`, `borderColor`, `textColor`, `created_at`, `updated_at`, `user_id_created`, `user_id_updated`) VALUES
(1, 'Rascunho', 'bg-secondary', '#6c757d', '#6c757d', '#fff', '2025-11-21 10:49:47', NULL, 1, 1),
(2, 'Roteiro em Revisão', 'bg-warning', '#ffc107', '#ffc107', '#fff', '2025-11-21 10:49:50', NULL, 1, 1),
(3, 'Roteiro Aprovado', 'bg-success', '#28a745', '#28a745', '#fff', '2025-11-21 10:49:57', NULL, 1, 1),
(4, 'Pronto para Gravação', 'bg-info', '#17a2b8', '#17a2b8', '#fff', '2025-11-21 10:49:54', NULL, 1, 1),
(5, 'Gravado', 'bg-primary', '#007bff', '#007bff', '#fff', '2025-11-21 10:49:59', NULL, 1, 1),
(6, 'Em Edição', 'bg-orange', '#ff851b', '#ff851b', '#fff', '2025-11-21 10:50:02', NULL, 1, 1),
(7, 'Aguardando Publicação', 'bg-teal', '#20c997', '#20c997', '#fff', '2025-11-21 10:50:06', NULL, 1, 1),
(8, 'Publicado', 'bg-purple', '#6f42c1', '#6f42c1', '#fff', '2025-11-21 10:50:10', NULL, 1, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbsys_youtube_channels`
--

CREATE TABLE `tbsys_youtube_channels` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `gcid` text NOT NULL,
  `customer_id` text NOT NULL,
  `channel_id` text NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `thumbnail` varchar(500) NOT NULL,
  `published_at` datetime DEFAULT NULL,
  `views` bigint(255) DEFAULT NULL,
  `subscribers` bigint(255) DEFAULT NULL,
  `videos` bigint(255) DEFAULT NULL,
  `workspace` int(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Despejando dados para a tabela `tbsys_youtube_channels`
--

INSERT INTO `tbsys_youtube_channels` (`id`, `gcid`, `customer_id`, `channel_id`, `title`, `description`, `thumbnail`, `published_at`, `views`, `subscribers`, `videos`, `workspace`, `created_at`, `updated_at`) VALUES
(4, '1b853843-450b-490a-b1ec-94125d9afa43', '931fb4c0-d829-48bf-b174-9165bbd0e04b', 'UC1wd52otyMceF4XP1OPwuDA', 'Ricardo Gomes | Dev', 'Somos apaixonados por tecnologia, e é por isso que estamos aqui.\nConfira dicas e informações sobre tecnologia e programação.', 'https://yt3.ggpht.com/hKT9K3T8neKUNmSV2RBYsuRSxTTreDDK5WhPr1A185Byl0t9HPvl1y9ktIsH9BexwH1Fhn2YHQ=s800-c-k-c0x00ffffff-no-rj', '2023-10-21 00:12:00', 60, 10, 2, 1, '2025-11-20 18:36:17', '2025-12-06 13:43:44'),
(10, '27bb28cd-d53b-4f8f-b0b4-44bb8da4ef24', '7192e5a9-f495-4951-89a1-ff5c285256f1', 'UC12tMU3K77cRol_D0Grf9lw', 'Jimmy Animes', 'Animes e mangás sempre para você.\n\nSomos um canal focados em animes e mangás, onde traremos sempre conteúdos atualizados sobre o que mais nos interessa, é claro: Animes e Mangas....\nVamos para os vídeos.\n\nContato: dfgcontatos@gmail.com\n', 'https://yt3.ggpht.com/cJWLBg1ZlYgaSvVLjwPosbnfMXapcU1TmQWsDqyW30j9lcXxe11MUaUK-R7NORwHyGOQTgN_tQ=s800-c-k-c0x00ffffff-no-rj', '2022-04-11 23:18:44', 244585, 1000, 82, 1, '2025-11-23 23:45:17', '2026-02-08 21:09:59'),
(13, 'aaa6c22d-3e2a-420d-8a08-d98b729dfdb2', '7192e5a9-f495-4951-89a1-ff5c285256f1', 'UC1wd52otyMceF4XP1OPwuDA', 'Ricardo Gomes | Dev', 'Somos apaixonados por tecnologia, e é por isso que estamos aqui.\nConfira dicas e informações sobre tecnologia e programação.\n', 'https://yt3.ggpht.com/hKT9K3T8neKUNmSV2RBYsuRSxTTreDDK5WhPr1A185Byl0t9HPvl1y9ktIsH9BexwH1Fhn2YHQ=s800-c-k-c0x00ffffff-no-rj', '2023-10-20 21:12:00', 60, 10, 2, 0, '2025-11-24 22:41:30', '2026-02-08 21:09:59');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbsys_youtube_tokens`
--

CREATE TABLE `tbsys_youtube_tokens` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `customer_id` text NOT NULL,
  `channel_id` varchar(100) NOT NULL,
  `access_token` text NOT NULL,
  `refresh_token` text NOT NULL,
  `expires_in` int(10) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `principal` int(1) NOT NULL DEFAULT 0,
  `create_token` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tbsys_youtube_tokens`
--

INSERT INTO `tbsys_youtube_tokens` (`id`, `customer_id`, `channel_id`, `access_token`, `refresh_token`, `expires_in`, `created_at`, `updated_at`, `principal`, `create_token`) VALUES
(7, '931fb4c0-d829-48bf-b174-9165bbd0e04b', 'UC1wd52otyMceF4XP1OPwuDA', 'ya29.a0ATi6K2s-bKD2f6S1XKC3LV4ZTFlb1s_dZLgalNqWb1Arua3VjKGuhMaBMGZaw5dE3AcydsNwxSV3HM0GVxEmgFoFXKuLrcyIpOZzFaGsiHc3PB5SSvY3WTidFEcA1WrOkiE19Z1QKNFn_nWU3OhtDhQ7sbLP4OlUArjh-e3q7EMk2lG3SFwpzkYW00JIICeFohdi-BEaCgYKARoSARcSFQHGX2Micco4bqb-UAGAaAgePc9S9w0206', '1//0hOKYD-1UcpJJCgYIARAAGBESNwF-L9Ir8kjVMyaVl_oCisj1v4neLv7cp9ly2KL3JOjVgddm8yQ-MmqZNQk2na1lZStn9Jy2Pe8', 3599, '2025-11-20 18:36:17', NULL, 0, 1763674577);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `tbsys_access_plans`
--
ALTER TABLE `tbsys_access_plans`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tbsys_access_plans_coupons`
--
ALTER TABLE `tbsys_access_plans_coupons`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tbsys_access_plans_price`
--
ALTER TABLE `tbsys_access_plans_price`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tbsys_ai_preferences`
--
ALTER TABLE `tbsys_ai_preferences`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tbsys_cloudflare_api`
--
ALTER TABLE `tbsys_cloudflare_api`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tbsys_company`
--
ALTER TABLE `tbsys_company`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tbsys_cron_emails`
--
ALTER TABLE `tbsys_cron_emails`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tbsys_cron_emails_files`
--
ALTER TABLE `tbsys_cron_emails_files`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tbsys_cron_emails_nfse`
--
ALTER TABLE `tbsys_cron_emails_nfse`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tbsys_currency`
--
ALTER TABLE `tbsys_currency`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tbsys_customer`
--
ALTER TABLE `tbsys_customer`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tbsys_customer_subscription_customer_payment`
--
ALTER TABLE `tbsys_customer_subscription_customer_payment`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tbsys_customer_usage_counters`
--
ALTER TABLE `tbsys_customer_usage_counters`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customer_id` (`customer_id`,`month_year`);

--
-- Índices de tabela `tbsys_departments`
--
ALTER TABLE `tbsys_departments`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tbsys_department_occupations`
--
ALTER TABLE `tbsys_department_occupations`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tbsys_language`
--
ALTER TABLE `tbsys_language`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tbsys_mailer`
--
ALTER TABLE `tbsys_mailer`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tbsys_notification`
--
ALTER TABLE `tbsys_notification`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tbsys_notification_type`
--
ALTER TABLE `tbsys_notification_type`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tbsys_payment_config`
--
ALTER TABLE `tbsys_payment_config`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tbsys_payment_config_brand`
--
ALTER TABLE `tbsys_payment_config_brand`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tbsys_payment_methods`
--
ALTER TABLE `tbsys_payment_methods`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tbsys_payment_status`
--
ALTER TABLE `tbsys_payment_status`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tbsys_privilege`
--
ALTER TABLE `tbsys_privilege`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tbsys_privilege_type`
--
ALTER TABLE `tbsys_privilege_type`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tbsys_privilege_type_privilege`
--
ALTER TABLE `tbsys_privilege_type_privilege`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tbsys_signatures`
--
ALTER TABLE `tbsys_signatures`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tbsys_signatures_auto_renew_history`
--
ALTER TABLE `tbsys_signatures_auto_renew_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `signature_id` (`signature_id`);

--
-- Índices de tabela `tbsys_signatures_history`
--
ALTER TABLE `tbsys_signatures_history`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tbsys_signatures_payments`
--
ALTER TABLE `tbsys_signatures_payments`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tbsys_signatures_payments_invoices`
--
ALTER TABLE `tbsys_signatures_payments_invoices`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tbsys_signatures_payments_status_history`
--
ALTER TABLE `tbsys_signatures_payments_status_history`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tbsys_signatures_plan_changes`
--
ALTER TABLE `tbsys_signatures_plan_changes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_signature_id` (`signature_id`),
  ADD KEY `idx_effective_date` (`effective_date`);

--
-- Índices de tabela `tbsys_signatures_terms`
--
ALTER TABLE `tbsys_signatures_terms`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tbsys_stconfig`
--
ALTER TABLE `tbsys_stconfig`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tbsys_ticket`
--
ALTER TABLE `tbsys_ticket`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tbsys_ticket_department`
--
ALTER TABLE `tbsys_ticket_department`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tbsys_ticket_department_subdepartment`
--
ALTER TABLE `tbsys_ticket_department_subdepartment`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tbsys_ticket_department_subdepartment_agent`
--
ALTER TABLE `tbsys_ticket_department_subdepartment_agent`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tbsys_ticket_department_subdepartment_priority`
--
ALTER TABLE `tbsys_ticket_department_subdepartment_priority`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tbsys_ticket_send`
--
ALTER TABLE `tbsys_ticket_send`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tbsys_ticket_send_file`
--
ALTER TABLE `tbsys_ticket_send_file`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tbsys_users`
--
ALTER TABLE `tbsys_users`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tbsys_videos_metrics`
--
ALTER TABLE `tbsys_videos_metrics`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tbsys_videos_scripts`
--
ALTER TABLE `tbsys_videos_scripts`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tbsys_videos_scripts_status`
--
ALTER TABLE `tbsys_videos_scripts_status`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tbsys_youtube_channels`
--
ALTER TABLE `tbsys_youtube_channels`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tbsys_youtube_tokens`
--
ALTER TABLE `tbsys_youtube_tokens`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `tbsys_access_plans`
--
ALTER TABLE `tbsys_access_plans`
  MODIFY `id` bigint(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `tbsys_access_plans_coupons`
--
ALTER TABLE `tbsys_access_plans_coupons`
  MODIFY `id` bigint(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `tbsys_access_plans_price`
--
ALTER TABLE `tbsys_access_plans_price`
  MODIFY `id` bigint(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `tbsys_ai_preferences`
--
ALTER TABLE `tbsys_ai_preferences`
  MODIFY `id` bigint(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `tbsys_cloudflare_api`
--
ALTER TABLE `tbsys_cloudflare_api`
  MODIFY `id` int(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `tbsys_company`
--
ALTER TABLE `tbsys_company`
  MODIFY `id` int(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `tbsys_cron_emails`
--
ALTER TABLE `tbsys_cron_emails`
  MODIFY `id` bigint(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT de tabela `tbsys_cron_emails_files`
--
ALTER TABLE `tbsys_cron_emails_files`
  MODIFY `id` bigint(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de tabela `tbsys_cron_emails_nfse`
--
ALTER TABLE `tbsys_cron_emails_nfse`
  MODIFY `id` bigint(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `tbsys_currency`
--
ALTER TABLE `tbsys_currency`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `tbsys_customer`
--
ALTER TABLE `tbsys_customer`
  MODIFY `id` bigint(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `tbsys_customer_subscription_customer_payment`
--
ALTER TABLE `tbsys_customer_subscription_customer_payment`
  MODIFY `id` bigint(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `tbsys_customer_usage_counters`
--
ALTER TABLE `tbsys_customer_usage_counters`
  MODIFY `id` bigint(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de tabela `tbsys_departments`
--
ALTER TABLE `tbsys_departments`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `tbsys_department_occupations`
--
ALTER TABLE `tbsys_department_occupations`
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de tabela `tbsys_language`
--
ALTER TABLE `tbsys_language`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `tbsys_mailer`
--
ALTER TABLE `tbsys_mailer`
  MODIFY `id` int(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `tbsys_notification`
--
ALTER TABLE `tbsys_notification`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT de tabela `tbsys_notification_type`
--
ALTER TABLE `tbsys_notification_type`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tbsys_payment_config`
--
ALTER TABLE `tbsys_payment_config`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `tbsys_payment_config_brand`
--
ALTER TABLE `tbsys_payment_config_brand`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `tbsys_payment_methods`
--
ALTER TABLE `tbsys_payment_methods`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `tbsys_payment_status`
--
ALTER TABLE `tbsys_payment_status`
  MODIFY `id` bigint(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de tabela `tbsys_privilege`
--
ALTER TABLE `tbsys_privilege`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `tbsys_privilege_type`
--
ALTER TABLE `tbsys_privilege_type`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT de tabela `tbsys_privilege_type_privilege`
--
ALTER TABLE `tbsys_privilege_type_privilege`
  MODIFY `id` bigint(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;

--
-- AUTO_INCREMENT de tabela `tbsys_signatures`
--
ALTER TABLE `tbsys_signatures`
  MODIFY `id` bigint(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de tabela `tbsys_signatures_auto_renew_history`
--
ALTER TABLE `tbsys_signatures_auto_renew_history`
  MODIFY `id` bigint(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de tabela `tbsys_signatures_history`
--
ALTER TABLE `tbsys_signatures_history`
  MODIFY `id` bigint(255) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tbsys_signatures_payments`
--
ALTER TABLE `tbsys_signatures_payments`
  MODIFY `id` bigint(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT de tabela `tbsys_signatures_payments_invoices`
--
ALTER TABLE `tbsys_signatures_payments_invoices`
  MODIFY `id` bigint(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de tabela `tbsys_signatures_payments_status_history`
--
ALTER TABLE `tbsys_signatures_payments_status_history`
  MODIFY `id` bigint(255) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT de tabela `tbsys_signatures_plan_changes`
--
ALTER TABLE `tbsys_signatures_plan_changes`
  MODIFY `id` bigint(255) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tbsys_signatures_terms`
--
ALTER TABLE `tbsys_signatures_terms`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `tbsys_stconfig`
--
ALTER TABLE `tbsys_stconfig`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `tbsys_ticket`
--
ALTER TABLE `tbsys_ticket`
  MODIFY `id` bigint(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `tbsys_ticket_department`
--
ALTER TABLE `tbsys_ticket_department`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `tbsys_ticket_department_subdepartment`
--
ALTER TABLE `tbsys_ticket_department_subdepartment`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `tbsys_ticket_department_subdepartment_agent`
--
ALTER TABLE `tbsys_ticket_department_subdepartment_agent`
  MODIFY `id` bigint(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=163;

--
-- AUTO_INCREMENT de tabela `tbsys_ticket_department_subdepartment_priority`
--
ALTER TABLE `tbsys_ticket_department_subdepartment_priority`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `tbsys_ticket_send`
--
ALTER TABLE `tbsys_ticket_send`
  MODIFY `id` bigint(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de tabela `tbsys_ticket_send_file`
--
ALTER TABLE `tbsys_ticket_send_file`
  MODIFY `id` bigint(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tbsys_users`
--
ALTER TABLE `tbsys_users`
  MODIFY `id` bigint(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `tbsys_videos_metrics`
--
ALTER TABLE `tbsys_videos_metrics`
  MODIFY `id` bigint(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tbsys_videos_scripts`
--
ALTER TABLE `tbsys_videos_scripts`
  MODIFY `id` bigint(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `tbsys_videos_scripts_status`
--
ALTER TABLE `tbsys_videos_scripts_status`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `tbsys_youtube_channels`
--
ALTER TABLE `tbsys_youtube_channels`
  MODIFY `id` bigint(255) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `tbsys_youtube_tokens`
--
ALTER TABLE `tbsys_youtube_tokens`
  MODIFY `id` bigint(255) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
