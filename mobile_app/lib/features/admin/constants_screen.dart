import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';

import '../../core/providers/app_providers.dart';
import '../../core/theme/app_theme.dart';
import '../shared/pos_widgets.dart';

class ConstantsHubScreen extends ConsumerStatefulWidget {
  const ConstantsHubScreen({super.key});

  @override
  ConsumerState<ConstantsHubScreen> createState() => _ConstantsHubScreenState();
}

class _ConstantsHubScreenState extends ConsumerState<ConstantsHubScreen> {
  List<Map<String, dynamic>> _configs = [];
  bool _loading = true;

  @override
  void initState() {
    super.initState();
    _load();
  }

  Future<void> _load() async {
    setState(() => _loading = true);
    _configs = await ref.read(opsRepositoryProvider).constantConfigs();
    setState(() => _loading = false);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Price Constants'), actions: [IconButton(icon: const Icon(Icons.refresh), onPressed: _load)]),
      body: _loading
          ? const Center(child: CircularProgressIndicator())
          : _configs.isEmpty
              ? const EmptyState(icon: Icons.table_chart, message: 'No constant tables configured')
              : ListView.builder(
                  padding: const EdgeInsets.all(12),
                  itemCount: _configs.length,
                  itemBuilder: (_, i) {
                    final c = _configs[i];
                    return Card(
                      child: ListTile(
                        title: Text(c['name']?.toString() ?? '', style: const TextStyle(fontWeight: FontWeight.w700)),
                        subtitle: Text(c['table']?.toString() ?? ''),
                        trailing: const Icon(Icons.chevron_right),
                        onTap: () => context.push('/admin/constants/${c['id']}'),
                      ),
                    );
                  },
                ),
    );
  }
}

class ConstantTableScreen extends ConsumerStatefulWidget {
  const ConstantTableScreen({super.key, required this.configId});

  final int configId;

  @override
  ConsumerState<ConstantTableScreen> createState() => _ConstantTableScreenState();
}

class _ConstantTableScreenState extends ConsumerState<ConstantTableScreen> {
  Map<String, dynamic> _data = {};
  bool _loading = true;

  @override
  void initState() {
    super.initState();
    _load();
  }

  Future<void> _load() async {
    setState(() => _loading = true);
    _data = await ref.read(opsRepositoryProvider).constantRows(widget.configId);
    setState(() => _loading = false);
  }

  @override
  Widget build(BuildContext context) {
    final config = _data['config'] as Map<String, dynamic>? ?? {};
    final columns = (_data['columns'] as List<dynamic>? ?? []).cast<String>();
    final rows = (_data['rows'] as List<dynamic>? ?? []).cast<Map<String, dynamic>>();

    return Scaffold(
      appBar: AppBar(title: Text(config['name']?.toString() ?? 'Constants')),
      floatingActionButton: FloatingActionButton(
        onPressed: () => _showAddRow(columns),
        child: const Icon(Icons.add),
      ),
      body: _loading
          ? const Center(child: CircularProgressIndicator())
          : ListView.builder(
              padding: const EdgeInsets.all(12),
              itemCount: rows.length,
              itemBuilder: (_, i) {
                final row = rows[i];
                final pk = columns.isNotEmpty ? columns.first : 'id';
                return Card(
                  margin: const EdgeInsets.only(bottom: 8),
                  child: ListTile(
                    title: Text(row.values.take(3).join(' · '), maxLines: 2),
                    trailing: IconButton(
                      icon: const Icon(Icons.delete_outline, color: AppColors.danger),
                      onPressed: () async {
                        await ref.read(opsRepositoryProvider).deleteConstantRow(widget.configId, pk, row[pk]);
                        _load();
                      },
                    ),
                  ),
                );
              },
            ),
    );
  }

  Future<void> _showAddRow(List<String> columns) async {
    final ctrls = {for (final c in columns) c: TextEditingController()};
    final ok = await showDialog<bool>(
      context: context,
      builder: (ctx) => AlertDialog(
        title: const Text('Add row'),
        content: SingleChildScrollView(
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: columns.map((c) => TextField(controller: ctrls[c], decoration: InputDecoration(labelText: c))).toList(),
          ),
        ),
        actions: [
          TextButton(onPressed: () => Navigator.pop(ctx, false), child: const Text('Cancel')),
          FilledButton(onPressed: () => Navigator.pop(ctx, true), child: const Text('Add')),
        ],
      ),
    );
    if (ok == true) {
      final row = <String, dynamic>{};
      for (final e in ctrls.entries) {
        row[e.key] = e.value.text;
      }
      await ref.read(opsRepositoryProvider).addConstantRow(widget.configId, row);
      _load();
    }
  }
}
