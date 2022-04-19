-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Апр 19 2022 г., 06:53
-- Версия сервера: 8.0.24
-- Версия PHP: 8.0.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `short_link`
--

-- --------------------------------------------------------

--
-- Структура таблицы `links`
--

CREATE TABLE `links` (
  `id` bigint UNSIGNED NOT NULL,
  `hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `url` varchar(255) NOT NULL,
  `created_at` int UNSIGNED NOT NULL,
  `updated_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `links`
--

INSERT INTO `links` (`id`, `hash`, `url`, `created_at`, `updated_at`) VALUES
(2, '12', 'https://vc.ruuu', 1650265234, 1650265234),
(4, '13242', 'https://vc.ru', 1650265263, 1650265263),
(5, '132424', 'https://vc.ru1', 1650265270, 1650265270),
(6, '20', 'https://ya.ru', 1650338871, 1650338871),
(8, '4871560', 'https://yaaa.ru', 1650339252, 1650339252),
(10, '4120626', 'https://ya.ru/', 1650340066, 1650340066);

-- --------------------------------------------------------

--
-- Структура таблицы `transitions`
--

CREATE TABLE `transitions` (
  `Id` bigint UNSIGNED NOT NULL,
  `link_id` bigint UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `transitions`
--

INSERT INTO `transitions` (`Id`, `link_id`, `created_at`) VALUES
(1, 4, 1650337014);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `token` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `name`, `token`) VALUES
(1, 'admin', 'token1234');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `links`
--
ALTER TABLE `links`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `url` (`url`),
  ADD UNIQUE KEY `hash` (`hash`),
  ADD KEY `created_at` (`created_at`),
  ADD KEY `updated_at` (`updated_at`);
ALTER TABLE `links` ADD FULLTEXT KEY `url_2` (`url`);

--
-- Индексы таблицы `transitions`
--
ALTER TABLE `transitions`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `link_id` (`link_id`),
  ADD KEY `created_at` (`created_at`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `links`
--
ALTER TABLE `links`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT для таблицы `transitions`
--
ALTER TABLE `transitions`
  MODIFY `Id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `transitions`
--
ALTER TABLE `transitions`
  ADD CONSTRAINT `transitions_ibfk_1` FOREIGN KEY (`link_id`) REFERENCES `links` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
