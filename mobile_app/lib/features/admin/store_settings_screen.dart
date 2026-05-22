import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../../core/providers/app_providers.dart';
import '../../core/theme/app_theme.dart';

class StoreSettingsScreen extends ConsumerStatefulWidget {
  const StoreSettingsScreen({super.key});

  @override
  ConsumerState<StoreSettingsScreen> createState() => _StoreSettingsScreenState();
}

class _StoreSettingsScreenState extends ConsumerState<StoreSettingsScreen> {
  Map<String, dynamic> _settings = {};
  bool _loading = true;
  bool _saving = false;

  final _nameCtrl = TextEditingController();
  final _branchCtrl = TextEditingController();
  final _phoneCtrl = TextEditingController();
  final _emailCtrl = TextEditingController();

  @override
  void dispose() {
    _nameCtrl.dispose();
    _branchCtrl.dispose();
    _phoneCtrl.dispose();
    _emailCtrl.dispose();
    super.dispose();
  }

  Future<void> _load() async {
    setState(() => _loading = true);
    _settings = await ref.read(opsRepositoryProvider).settings();
    final store = _settings['store'] as Map<String, dynamic>? ?? {};
    _nameCtrl.text = store['name']?.toString() ?? '';
    _branchCtrl.text = store['branch']?.toString() ?? '';
    _phoneCtrl.text = store['phone']?.toString() ?? '';
    _emailCtrl.text = store['email']?.toString() ?? '';
    setState(() => _loading = false);
  }

  @override
  void initState() {
    super.initState();
    _load();
  }

  Future<void> _save() async {
    setState(() => _saving = true);
    try {
      final modules = _settings['modules'] as Map<String, dynamic>? ?? {};
      await ref.read(opsRepositoryProvider).updateSettings({
        'store': {
          'name': _nameCtrl.text,
          'branch': _branchCtrl.text,
          'phone': _phoneCtrl.text,
          'email': _emailCtrl.text,
        },
        'modules': modules,
      });
      if (mounted) ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Settings saved')));
      await _load();
    } finally {
      if (mounted) setState(() => _saving = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    final modules = (_settings['modules'] as Map<String, dynamic>? ?? {});

    return Scaffold(
      appBar: AppBar(title: const Text('Store & Modules')),
      body: _loading
          ? const Center(child: CircularProgressIndicator())
          : ListView(
              padding: const EdgeInsets.all(16),
              children: [
                const Text('Company', style: TextStyle(fontWeight: FontWeight.w800, fontSize: 16)),
                const SizedBox(height: 8),
                TextField(controller: _nameCtrl, decoration: const InputDecoration(labelText: 'Company name')),
                TextField(controller: _branchCtrl, decoration: const InputDecoration(labelText: 'Store / branch name')),
                TextField(controller: _phoneCtrl, decoration: const InputDecoration(labelText: 'Phone')),
                TextField(controller: _emailCtrl, decoration: const InputDecoration(labelText: 'Email')),
                const SizedBox(height: 20),
                const Text('Product modules', style: TextStyle(fontWeight: FontWeight.w800, fontSize: 16)),
                const SizedBox(height: 8),
                ...modules.entries.map((e) {
                  final on = e.value == true;
                  return SwitchListTile(
                    title: Text(e.key[0].toUpperCase() + e.key.substring(1)),
                    value: on,
                    onChanged: (v) {
                      setState(() {
                        modules[e.key] = v;
                        _settings = {..._settings, 'modules': Map<String, dynamic>.from(modules)};
                      });
                    },
                  );
                }),
                const SizedBox(height: 24),
                FilledButton(
                  onPressed: _saving ? null : _save,
                  child: Text(_saving ? 'Saving...' : 'Save settings'),
                ),
              ],
            ),
    );
  }
}
