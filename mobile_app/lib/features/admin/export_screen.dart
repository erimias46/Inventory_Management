import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../../core/providers/app_providers.dart';
import '../../core/theme/app_theme.dart';

class ExportScreen extends ConsumerStatefulWidget {
  const ExportScreen({super.key});

  @override
  ConsumerState<ExportScreen> createState() => _ExportScreenState();
}

class _ExportScreenState extends ConsumerState<ExportScreen> {
  final _tableCtrl = TextEditingController(text: 'shoes');
  List<Map<String, dynamic>> _rows = [];
  List<String> _columns = [];
  bool _loading = false;

  static const _presets = [
    'shoes', 'jeans', 'top', 'sales', 'shoes_sales', 'delivery', 'multi_sale', 'customer', 'user',
  ];

  Future<void> _export() async {
    setState(() => _loading = true);
    try {
      final data = await ref.read(opsRepositoryProvider).exportTable(_tableCtrl.text.trim());
      setState(() {
        _columns = (data['columns'] as List<dynamic>? ?? []).cast<String>();
        _rows = (data['rows'] as List<dynamic>? ?? []).cast<Map<String, dynamic>>();
      });
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('${_rows.length} rows loaded')));
      }
    } finally {
      if (mounted) setState(() => _loading = false);
    }
  }

  String _tsv() {
    final buf = StringBuffer();
    buf.writeln(_columns.join('\t'));
    for (final row in _rows) {
      buf.writeln(_columns.map((c) => row[c]?.toString() ?? '').join('\t'));
    }
    return buf.toString();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Export Data')),
      body: Column(
        children: [
          Padding(
            padding: const EdgeInsets.all(12),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.stretch,
              children: [
                TextField(
                  controller: _tableCtrl,
                  decoration: const InputDecoration(labelText: 'Table name', hintText: 'shoes_sales'),
                ),
                const SizedBox(height: 8),
                Wrap(
                  spacing: 6,
                  children: _presets.map((t) => ActionChip(label: Text(t), onPressed: () => _tableCtrl.text = t)).toList(),
                ),
                const SizedBox(height: 12),
                FilledButton(onPressed: _loading ? null : _export, child: Text(_loading ? 'Loading...' : 'Load for export')),
                if (_rows.isNotEmpty) ...[
                  const SizedBox(height: 8),
                  OutlinedButton.icon(
                    onPressed: () {
                      Clipboard.setData(ClipboardData(text: _tsv()));
                      ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Copied TSV to clipboard')));
                    },
                    icon: const Icon(Icons.copy),
                    label: const Text('Copy as spreadsheet (TSV)'),
                  ),
                ],
              ],
            ),
          ),
          Expanded(
            child: _rows.isEmpty
                ? const Center(child: Text('Load a table to preview', style: TextStyle(color: AppColors.textMuted)))
                : ListView.builder(
                    itemCount: _rows.length.clamp(0, 200),
                    itemBuilder: (_, i) {
                      final row = _rows[i];
                      return ListTile(
                        dense: true,
                        title: Text(row.values.take(4).join(' | '), maxLines: 2, style: const TextStyle(fontSize: 12)),
                      );
                    },
                  ),
          ),
        ],
      ),
    );
  }
}
