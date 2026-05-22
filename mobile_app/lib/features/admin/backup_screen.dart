import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../../core/providers/app_providers.dart';
import '../../core/theme/app_theme.dart';
import '../shared/pos_widgets.dart';

class BackupScreen extends ConsumerStatefulWidget {
  const BackupScreen({super.key});

  @override
  ConsumerState<BackupScreen> createState() => _BackupScreenState();
}

class _BackupScreenState extends ConsumerState<BackupScreen> {
  List<Map<String, dynamic>> _files = [];
  bool _loading = true;
  bool _creating = false;

  @override
  void initState() {
    super.initState();
    _load();
  }

  Future<void> _load() async {
    setState(() => _loading = true);
    _files = await ref.read(opsRepositoryProvider).backups();
    setState(() => _loading = false);
  }

  Future<void> _create() async {
    setState(() => _creating = true);
    try {
      final r = await ref.read(opsRepositoryProvider).createBackup();
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Created ${r['filename'] ?? 'backup'}')),
        );
      }
      await _load();
    } finally {
      if (mounted) setState(() => _creating = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Database Backup')),
      body: Column(
        children: [
          Padding(
            padding: const EdgeInsets.all(16),
            child: FilledButton.icon(
              onPressed: _creating ? null : _create,
              icon: _creating ? const SizedBox(width: 18, height: 18, child: CircularProgressIndicator(strokeWidth: 2)) : const Icon(Icons.backup),
              label: Text(_creating ? 'Creating backup...' : 'Create new backup'),
              style: FilledButton.styleFrom(minimumSize: const Size.fromHeight(48)),
            ),
          ),
          Expanded(
            child: _loading
                ? const Center(child: CircularProgressIndicator())
                : _files.isEmpty
                    ? const EmptyState(icon: Icons.folder_open, message: 'No .sql files in backups/')
                    : ListView.builder(
                        padding: const EdgeInsets.symmetric(horizontal: 12),
                        itemCount: _files.length,
                        itemBuilder: (_, i) {
                          final f = _files[i];
                          final sizeKb = ((f['size'] as num?) ?? 0) / 1024;
                          return Card(
                            child: ListTile(
                              leading: const Icon(Icons.description, color: AppColors.accent),
                              title: Text(f['filename']?.toString() ?? ''),
                              subtitle: Text('${f['modified']} · ${sizeKb.toStringAsFixed(1)} KB'),
                            ),
                          );
                        },
                      ),
          ),
        ],
      ),
    );
  }
}
