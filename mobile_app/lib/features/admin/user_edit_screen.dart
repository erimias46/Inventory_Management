import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../../core/network/api_exception.dart';
import '../../core/providers/app_providers.dart';
import '../../core/theme/app_theme.dart';

class UserEditScreen extends ConsumerStatefulWidget {
  const UserEditScreen({super.key, this.userId});

  final int? userId;

  @override
  ConsumerState<UserEditScreen> createState() => _UserEditScreenState();
}

class _UserEditScreenState extends ConsumerState<UserEditScreen> {
  final _nameCtrl = TextEditingController();
  final _passCtrl = TextEditingController();
  String _privilege = 'user';
  Map<String, int> _modules = {};
  List<Map<String, dynamic>> _moduleDefs = [];
  bool _loading = true;
  bool _saving = false;
  bool get _isNew => widget.userId == null;

  @override
  void dispose() {
    _nameCtrl.dispose();
    _passCtrl.dispose();
    super.dispose();
  }

  Future<void> _load() async {
    _moduleDefs = await ref.read(adminRepositoryProvider).userModuleKeys();
    if (!_isNew) {
      final u = await ref.read(adminRepositoryProvider).getUser(widget.userId!);
      _nameCtrl.text = u['user_name']?.toString() ?? '';
      _passCtrl.text = u['password']?.toString() ?? '';
      _privilege = u['privilege']?.toString() ?? 'user';
      final mods = u['modules'] as Map<String, dynamic>? ?? {};
      _modules = mods.map((k, v) => MapEntry(k, (v == 1 || v == true) ? 1 : 0));
    } else {
      for (final d in _moduleDefs) {
        _modules[d['key'] as String] = 0;
      }
    }
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
      final body = {
        'user_name': _nameCtrl.text.trim(),
        'password': _passCtrl.text,
        'privilege': _privilege,
        'modules': _modules,
      };
      if (_isNew) {
        await ref.read(adminRepositoryProvider).createUser(
              userName: body['user_name'] as String,
              password: body['password'] as String,
              privilege: _privilege,
              modules: _modules,
            );
      } else {
        await ref.read(adminRepositoryProvider).updateUser(widget.userId!, body);
      }
      if (mounted) Navigator.pop(context, true);
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text(e is ApiException ? e.message : '$e'), backgroundColor: AppColors.danger),
        );
      }
    } finally {
      if (mounted) setState(() => _saving = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    final groups = <String, List<Map<String, dynamic>>>{};
    for (final d in _moduleDefs) {
      final g = d['group']?.toString() ?? 'Other';
      groups.putIfAbsent(g, () => []).add(d);
    }

    return Scaffold(
      appBar: AppBar(title: Text(_isNew ? 'New User' : 'Edit User')),
      body: _loading
          ? const Center(child: CircularProgressIndicator())
          : ListView(
              padding: const EdgeInsets.all(16),
              children: [
                TextField(controller: _nameCtrl, decoration: const InputDecoration(labelText: 'Username')),
                TextField(controller: _passCtrl, decoration: const InputDecoration(labelText: 'Password'), obscureText: false),
                DropdownButtonFormField<String>(
                  initialValue: _privilege,
                  decoration: const InputDecoration(labelText: 'Privilege'),
                  items: const [
                    DropdownMenuItem(value: 'user', child: Text('User')),
                    DropdownMenuItem(value: 'admin', child: Text('Admin')),
                  ],
                  onChanged: (v) => setState(() => _privilege = v ?? 'user'),
                ),
                const SizedBox(height: 16),
                const Text('Permissions', style: TextStyle(fontWeight: FontWeight.w800, fontSize: 16)),
                ...groups.entries.expand((e) => [
                      Padding(
                        padding: const EdgeInsets.only(top: 12, bottom: 4),
                        child: Text(e.key, style: const TextStyle(color: AppColors.textMuted, fontWeight: FontWeight.w600)),
                      ),
                      ...e.value.map((d) {
                        final key = d['key'] as String;
                        return SwitchListTile(
                          dense: true,
                          title: Text(d['label']?.toString() ?? key, style: const TextStyle(fontSize: 13)),
                          value: (_modules[key] ?? 0) == 1,
                          onChanged: (v) => setState(() => _modules[key] = v ? 1 : 0),
                        );
                      }),
                    ]),
                const SizedBox(height: 24),
                FilledButton(
                  onPressed: _saving ? null : _save,
                  child: Text(_saving ? 'Saving...' : (_isNew ? 'Create user' : 'Save changes')),
                ),
              ],
            ),
    );
  }
}
