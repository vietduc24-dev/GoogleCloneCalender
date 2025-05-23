CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `calendars`
--

CREATE TABLE `calendars` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `calendars`
--

INSERT INTO `calendars` (`id`, `user_id`, `name`, `description`, `color`, `created_at`, `updated_at`) VALUES
(1, 1, 'Lịch của tôi', 'Lịch mặc định', '#4285F4', '2025-05-14 23:21:34', '2025-05-14 23:21:34');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `calendar_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'meeting',
  `is_all_day` tinyint(1) NOT NULL DEFAULT '0',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'confirmed',
  `notification` int(11) NOT NULL DEFAULT '30',
  `color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `calendar_id`, `title`, `description`, `start_time`, `end_time`, `location`, `type`, `is_all_day`, `status`, `notification`, `color`, `created_at`, `updated_at`) VALUES
(2, 1, 'aa', 'aa', '2025-04-26 17:00:00', '2025-04-27 17:00:00', 'aa', 'meeting', 1, 'confirmed', 30, '#4285F4', '2025-05-14 23:36:50', '2025-05-15 00:20:54'),
(3, 1, 'âssss', 'dd', '2025-05-07 17:00:00', '2025-05-09 17:00:00', 'dffsss', 'meeting', 1, 'confirmed', 30, '#34A853', '2025-05-15 00:32:35', '2025-05-15 01:42:35'),
(4, 1, 'cho vui', 'nô', '2025-05-15 07:42:00', '2025-05-17 08:42:00', 'nhà riêng', 'meeting', 0, 'confirmed', 30, '#4285F4', '2025-05-15 00:42:34', '2025-05-15 00:42:34'),
(5, 1, '1231231', NULL, '0110-12-31 16:53:30', '2025-05-06 17:00:00', NULL, 'meeting', 1, 'confirmed', 30, '#4285F4', '2025-05-15 00:43:29', '2025-05-15 00:43:29'),
(6, 1, 'sdfgh', NULL, '2025-05-08 17:00:00', '2025-05-09 17:00:00', NULL, 'meeting', 1, 'confirmed', 30, '#EA4335', '2025-05-15 01:40:49', '2025-05-15 01:40:49');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '2024_03_19_000001_create_calendars_table', 1),
(4, '2024_03_19_000002_create_events_table', 1),
(5, '2024_03_19_000003_create_reminders_table', 1),
(6, '2024_03_19_000004_create_shared_calendars_table', 1),
(7, '2025_05_13_021455_create_personal_access_tokens_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 1, 'auth_token', 'f7f1cc6cb8c16bea512ad4a03018c2cdf49c9c26da979cefa5e2db7044cdac93', '[\"*\"]', '2025-05-15 00:24:31', NULL, '2025-05-14 23:21:15', '2025-05-15 00:24:31'),
(2, 'App\\Models\\User', 1, 'auth_token', 'bd94df1e33d6e6a237401054d8d6018b65f238ce16f4166c27ad85488b281efa', '[\"*\"]', '2025-05-14 23:21:52', NULL, '2025-05-14 23:21:33', '2025-05-14 23:21:52'),
(6, 'App\\Models\\User', 1, 'auth_token', 'c4f6a4e1e9838403bf833e02ee2a88c1a1deab0a604470ef86f4bcc82723880a', '[\"*\"]', '2025-05-15 02:30:17', NULL, '2025-05-15 02:16:14', '2025-05-15 02:30:17');

-- --------------------------------------------------------

--
-- Table structure for table `reminders`
--

CREATE TABLE `reminders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reminder_time` datetime NOT NULL,
  `method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'notification',
  `color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#4285F4',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reminders`
--

INSERT INTO `reminders` (`id`, `user_id`, `title`, `reminder_time`, `method`, `color`, `created_at`, `updated_at`) VALUES
(3, 1, 'aaa', '2025-05-11 12:04:00', 'notification', '#4285F4', '2025-05-14 23:25:54', '2025-05-14 23:47:22'),
(4, 1, 'đi chơi update', '2025-01-01 10:04:00', 'email', '#4285F4', '2025-05-14 23:26:19', '2025-05-15 00:42:58'),
(5, 1, 'test time', '2025-05-21 09:00:00', 'notification', '#4285F4', '2025-05-14 23:33:25', '2025-05-15 00:21:10'),
(6, 1, 'aa', '2025-05-15 12:00:00', 'email', '#4285F4', '2025-05-14 23:36:20', '2025-05-14 23:36:20'),
(7, 1, 'aa', '2025-05-17 16:00:00', 'notification', '#4285F4', '2025-05-14 23:40:00', '2025-05-14 23:40:25'),
(8, 1, 'aaaa', '2025-05-19 09:00:00', 'both', '#4285F4', '2025-05-14 23:43:42', '2025-05-14 23:43:42'),
(9, 1, 'aa', '2025-05-06 17:00:00', 'notification', '#EA4335', '2025-05-14 23:49:25', '2025-05-15 01:40:40'),
(10, 1, 'hehehe', '2025-05-12 02:00:00', 'notification', '#4285F4', '2025-05-14 23:54:59', '2025-05-14 23:54:59'),
(11, 1, 'cay k ta', '2024-12-31 21:00:00', 'email', '#4285F4', '2025-05-14 23:57:47', '2025-05-15 00:43:07'),
(12, 1, 'fghgg', '2025-05-05 23:00:00', 'notification', '#4285F4', '2025-05-15 00:35:45', '2025-05-15 00:35:45'),
(13, 1, 'hhh', '2025-05-05 23:00:00', 'notification', '#4285F4', '2025-05-15 00:36:01', '2025-05-15 00:36:01'),
(14, 1, 'rrrr', '2025-05-13 17:00:00', 'email', '#EA4335', '2025-05-15 01:42:26', '2025-05-15 01:42:26'),
(15, 1, 'ssss', '2025-05-19 17:00:00', 'both', '#34A853', '2025-05-15 02:08:28', '2025-05-15 02:08:28');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shared_calendars`
--

CREATE TABLE `shared_calendars` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `calendar_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `permission` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'read',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'test', 'ductest3@gmail.com', NULL, '$2y$12$ddpRbmdg.2gr9DlTzK09u.OXPxKk7UgHSMXT0Hgav4F7sHb7jpAjq', NULL, '2025-05-14 23:21:15', '2025-05-14 23:21:15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `calendars`
--
ALTER TABLE `calendars`
  ADD PRIMARY KEY (`id`),
  ADD KEY `calendars_user_id_foreign` (`user_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `events_calendar_id_foreign` (`calendar_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `reminders`
--
ALTER TABLE `reminders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reminders_user_id_foreign` (`user_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `shared_calendars`
--
ALTER TABLE `shared_calendars`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `shared_calendars_calendar_id_user_id_unique` (`calendar_id`,`user_id`),
  ADD KEY `shared_calendars_user_id_foreign` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `calendars`
--
ALTER TABLE `calendars`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `reminders`
--
ALTER TABLE `reminders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `shared_calendars`
--
ALTER TABLE `shared_calendars`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `calendars`
--
ALTER TABLE `calendars`
  ADD CONSTRAINT `calendars_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_calendar_id_foreign` FOREIGN KEY (`calendar_id`) REFERENCES `calendars` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reminders`
--
ALTER TABLE `reminders`
  ADD CONSTRAINT `reminders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `shared_calendars`
--
ALTER TABLE `shared_calendars`
  ADD CONSTRAINT `shared_calendars_calendar_id_foreign` FOREIGN KEY (`calendar_id`) REFERENCES `calendars` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `shared_calendars_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
