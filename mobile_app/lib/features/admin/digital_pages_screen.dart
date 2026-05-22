import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../../core/providers/app_providers.dart';
import '../../core/theme/app_theme.dart';
import '../shared/pos_widgets.dart';

class DigitalPagesScreen extends ConsumerStatefulWidget {
  const DigitalPagesScreen({super.key});

  @override
  ConsumerState<DigitalPagesScreen> createState() => _DigitalPagesScreenState();
}

class _DigitalPagesScreenState extends ConsumerState<DigitalPagesScreen> {
  List<Map<String, dynamic>> _pages = [];
  bool _loading = true;

  @override
  void initState() {
    super.initState();
    _load();
  }

  Future<void> _load() async {
    setState(() => _loading = true);
    _pages = await ref.read(opsRepositoryProvider).digitalPages();
    setState(() => _loading = false);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Digital Pages (Top)')),
      body: _loading
          ? const Center(child: CircularProgressIndicator())
          : _pages.isEmpty
              ? const EmptyState(icon: Icons.print_disabled, message: 'No single_page records (Top module)')
              : ListView.builder(
                  padding: const EdgeInsets.all(12),
                  itemCount: _pages.length,
                  itemBuilder: (_, i) {
                    final p = _pages[i];
                    return Card(
                      child: ListTile(
                        title: Text(p['customer']?.toString() ?? 'Job #${p['single_page_id']}', style: const TextStyle(fontWeight: FontWeight.w700)),
                        subtitle: Text('${p['job_type'] ?? ''} · ${p['size'] ?? ''} · ${p['total_price'] ?? ''}'),
                      ),
                    );
                  },
                ),
    );
  }
}
