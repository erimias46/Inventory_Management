import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';

import '../../core/config/api_config.dart';
import '../../core/providers/app_providers.dart';
import '../../core/theme/app_theme.dart';
import '../auth/auth_repository.dart';
import '../shared/connection_banner.dart';

class SettingsScreen extends ConsumerStatefulWidget {
  const SettingsScreen({super.key});

  @override
  ConsumerState<SettingsScreen> createState() => _SettingsScreenState();
}

class _SettingsScreenState extends ConsumerState<SettingsScreen> {
  late TextEditingController _urlCtrl;
  bool _testing = false;
  HealthStatus? _status;

  @override
  void initState() {
    super.initState();
    _urlCtrl = TextEditingController();
    _load();
  }

  Future<void> _load() async {
    _urlCtrl.text = await ApiConfig.getBaseUrl();
    setState(() {});
  }

  Future<void> _save() async {
    await ApiConfig.setBaseUrl(_urlCtrl.text.trim());
    await ref.read(apiClientProvider).resetClient();
    ref.invalidate(connectionStatusProvider);
    if (mounted) {
      ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('API URL saved')));
    }
  }

  Future<void> _test() async {
    setState(() {
      _testing = true;
      _status = null;
    });
    await ApiConfig.setBaseUrl(_urlCtrl.text.trim());
    await ref.read(apiClientProvider).resetClient();
    final status = await ref.read(authRepositoryProvider).connectionDiagnostics();
    setState(() {
      _testing = false;
      _status = status;
    });
  }

  @override
  Widget build(BuildContext context) {
    final connected = _status?.fullyConnected == true;
    final user = ref.watch(currentUserProvider);

    return Scaffold(
      appBar: AppBar(title: const Text('Settings')),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            if (user != null && user.shopName.isNotEmpty)
              Card(
                color: AppColors.accent.withValues(alpha: 0.12),
                child: ListTile(
                  leading: const Icon(Icons.storefront, color: AppColors.accent),
                  title: Text(user.shopName, style: const TextStyle(fontWeight: FontWeight.w700)),
                  subtitle: user.shopSlug.isNotEmpty ? Text('Shop: ${user.shopSlug}') : null,
                ),
              ),
            if (user != null && user.shopName.isNotEmpty) const SizedBox(height: 12),
            Card(
              child: Padding(
                padding: const EdgeInsets.all(16),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Text('Server URL', style: TextStyle(fontWeight: FontWeight.w700)),
                    const SizedBox(height: 4),
                    const Text(
                      'Must end with /index.php on MAMP',
                      style: TextStyle(color: AppColors.textMuted, fontSize: 12),
                    ),
                    const SizedBox(height: 12),
                    TextField(
                      controller: _urlCtrl,
                      maxLines: 2,
                      decoration: const InputDecoration(hintText: 'http://127.0.0.1:8888/stock/api/v1/index.php'),
                    ),
                  ],
                ),
              ),
            ),
            const SizedBox(height: 12),
            Wrap(
              spacing: 8,
              children: [
                ActionChip(label: const Text('Android'), onPressed: () => _urlCtrl.text = ApiConfig.androidBase),
                ActionChip(label: const Text('iOS'), onPressed: () => _urlCtrl.text = ApiConfig.iosBase),
                ActionChip(label: const Text('Production'), onPressed: () => _urlCtrl.text = ApiConfig.prodBase),
              ],
            ),
            if (_status != null) ...[
              const SizedBox(height: 16),
              Card(
                color: connected
                    ? AppColors.success.withValues(alpha: 0.15)
                    : AppColors.danger.withValues(alpha: 0.15),
                child: ListTile(
                  leading: Icon(
                    connected ? Icons.cloud_done : Icons.cloud_off,
                    color: connected ? AppColors.success : AppColors.danger,
                  ),
                  title: Text(
                    connected ? 'Fully connected' : 'Connection issue',
                    style: const TextStyle(fontWeight: FontWeight.w700),
                  ),
                  subtitle: Text(_status!.message ?? ''),
                ),
              ),
            ],
            const SizedBox(height: 12),
            OutlinedButton.icon(
              onPressed: () => context.push('/profile'),
              icon: const Icon(Icons.person_outline),
              label: const Text('My profile'),
            ),
            if (user?.isMasterAdmin == true) ...[
              const SizedBox(height: 8),
              OutlinedButton.icon(
                onPressed: () => context.push('/admin/store-settings'),
                icon: const Icon(Icons.store),
                label: const Text('Store & module settings'),
              ),
            ],
            const Spacer(),
            FilledButton(
              onPressed: _testing ? null : _test,
              child: Text(_testing ? 'Testing API & database...' : 'Test connection'),
            ),
            const SizedBox(height: 8),
            OutlinedButton(onPressed: _save, child: const Text('Save URL')),
          ],
        ),
      ),
    );
  }
}
