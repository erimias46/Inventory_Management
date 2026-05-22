import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:intl/intl.dart';

import '../../core/providers/app_providers.dart';
import '../../core/theme/app_theme.dart';
import '../shared/pos_widgets.dart';

/// Multi-sale batch log (web: pages/sale/multi_log.php).
class SalesLogsScreen extends ConsumerStatefulWidget {
  const SalesLogsScreen({super.key});

  @override
  ConsumerState<SalesLogsScreen> createState() => _SalesLogsScreenState();
}

class _SalesLogsScreenState extends ConsumerState<SalesLogsScreen> {
  List<Map<String, dynamic>> _logs = [];
  bool _loading = true;

  @override
  void initState() {
    super.initState();
    _load();
  }

  Future<void> _load() async {
    setState(() => _loading = true);
    try {
      _logs = await ref.read(salesRepositoryProvider).multiSaleLogs();
    } finally {
      if (mounted) setState(() => _loading = false);
    }
  }

  String _fmt(String? raw) {
    if (raw == null) return '';
    try {
      return DateFormat('MMM d, yyyy · HH:mm').format(DateTime.parse(raw));
    } catch (_) {
      return raw;
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Multi Sale Logs'),
        actions: [IconButton(onPressed: _load, icon: const Icon(Icons.refresh))],
      ),
      body: _loading
          ? const Center(child: CircularProgressIndicator())
          : _logs.isEmpty
              ? const EmptyState(icon: Icons.history, message: 'No multi-sale logs yet')
              : ListView.builder(
                  padding: const EdgeInsets.all(12),
                  itemCount: _logs.length,
                  itemBuilder: (_, i) {
                    final log = _logs[i];
                    return Card(
                      margin: const EdgeInsets.only(bottom: 8),
                      child: ListTile(
                        title: Text(
                          'Batch #${log['multi_id'] ?? log['id'] ?? ''}',
                          style: const TextStyle(fontWeight: FontWeight.w700),
                        ),
                        subtitle: Text(
                          '${log['from_table'] ?? ''} · sale #${log['sales_id'] ?? ''}\n${_fmt(log['created_at']?.toString())}',
                        ),
                        isThreeLine: true,
                        trailing: const Icon(Icons.receipt_long_outlined, color: AppColors.textMuted),
                      ),
                    );
                  },
                ),
    );
  }
}
