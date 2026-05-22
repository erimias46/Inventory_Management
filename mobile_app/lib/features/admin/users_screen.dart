import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';

import '../../core/providers/app_providers.dart';
import '../../core/theme/app_theme.dart';
import '../../core/utils/json_parse.dart';

class UsersScreen extends ConsumerStatefulWidget {
  const UsersScreen({super.key});

  @override
  ConsumerState<UsersScreen> createState() => _UsersScreenState();
}

class _UsersScreenState extends ConsumerState<UsersScreen> {
  List<Map<String, dynamic>> _users = [];
  bool _loading = true;

  @override
  void initState() {
    super.initState();
    _load();
  }

  Future<void> _load() async {
    setState(() => _loading = true);
    _users = await ref.read(adminRepositoryProvider).users();
    setState(() => _loading = false);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Users'),
        actions: [IconButton(icon: const Icon(Icons.refresh), onPressed: _load)],
      ),
      floatingActionButton: FloatingActionButton.extended(
        onPressed: () async {
          final ok = await context.push<bool>('/admin/users/new');
          if (ok == true) _load();
        },
        icon: const Icon(Icons.person_add),
        label: const Text('Add user'),
      ),
      body: _loading
          ? const Center(child: CircularProgressIndicator())
          : ListView.builder(
              padding: const EdgeInsets.all(12),
              itemCount: _users.length,
              itemBuilder: (_, i) {
                final u = _users[i];
                final name = u['user_name']?.toString() ?? '';
                final isAdmin = u['is_master_admin'] == true;
                final id = parseJsonInt(u['id'], 0);
                return Card(
                  margin: const EdgeInsets.only(bottom: 8),
                  child: ListTile(
                    onTap: isAdmin
                        ? null
                        : () async {
                            final ok = await context.push<bool>('/admin/users/$id');
                            if (ok == true) _load();
                          },
                    leading: CircleAvatar(
                      backgroundColor: isAdmin ? AppColors.warning.withValues(alpha: 0.2) : AppColors.accent.withValues(alpha: 0.2),
                      child: Text(name.isNotEmpty ? name[0].toUpperCase() : '?', style: TextStyle(fontWeight: FontWeight.w800, color: isAdmin ? AppColors.warning : AppColors.accent)),
                    ),
                    title: Text(name, style: const TextStyle(fontWeight: FontWeight.w700)),
                    subtitle: Text(u['privilege']?.toString() ?? ''),
                    trailing: isAdmin
                        ? const Icon(Icons.star, color: AppColors.warning)
                        : IconButton(
                            icon: const Icon(Icons.delete_outline, color: AppColors.danger),
                            onPressed: () async {
                              final ok = await showDialog<bool>(
                                context: context,
                                builder: (ctx) => AlertDialog(
                                  title: const Text('Delete user?'),
                                  actions: [
                                    TextButton(onPressed: () => Navigator.pop(ctx, false), child: const Text('Cancel')),
                                    FilledButton(onPressed: () => Navigator.pop(ctx, true), child: const Text('Delete')),
                                  ],
                                ),
                              );
                              if (ok == true) {
                                await ref.read(adminRepositoryProvider).deleteUser(id);
                                _load();
                              }
                            },
                          ),
                  ),
                );
              },
            ),
    );
  }
}
