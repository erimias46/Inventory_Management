import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../../core/providers/app_providers.dart';
import '../../core/theme/app_theme.dart';
import '../shared/pos_widgets.dart';

class VerifyScreen extends ConsumerStatefulWidget {
  const VerifyScreen({super.key});

  @override
  ConsumerState<VerifyScreen> createState() => _VerifyScreenState();
}

class _VerifyScreenState extends ConsumerState<VerifyScreen> {
  static const _types = [
    ('jeans', 'Jeans'),
    ('shoes', 'Shoes'),
    ('top', 'Top'),
    ('complete', 'Complete'),
    ('accessory', 'Accessory'),
    ('wig', 'Wig'),
    ('cosmetics', 'Cosmetics'),
  ];

  String _type = 'jeans';
  List<Map<String, dynamic>> _queue = [];
  bool _loading = true;

  @override
  void initState() {
    super.initState();
    _load();
  }

  Future<void> _load() async {
    setState(() => _loading = true);
    _queue = await ref.read(salesRepositoryProvider).verifyQueue(_type);
    setState(() => _loading = false);
  }

  Future<void> _approve(Map<String, dynamic> item) async {
    final id = int.parse(item['id'].toString());
    final nameKey = '${_type}_name';
    await ref.read(salesRepositoryProvider).approveVerify(_type, id);
    if (mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Approved ${item[nameKey]}'), backgroundColor: AppColors.success),
      );
    }
    _load();
  }

  String _errorLabel(dynamic e) => switch (e?.toString()) {
        '1' => 'New product',
        '2' => 'Low stock',
        _ => 'Review',
      };

  @override
  Widget build(BuildContext context) {
    final nameKey = '${_type}_name';
    return Scaffold(
      appBar: AppBar(
        title: const Text('Verify Stock'),
        actions: [IconButton(icon: const Icon(Icons.refresh), onPressed: _load)],
      ),
      body: Column(
        children: [
          Padding(
            padding: const EdgeInsets.all(12),
            child: SingleChildScrollView(
              scrollDirection: Axis.horizontal,
              child: Row(
                children: _types.map((t) {
                  final sel = t.$1 == _type;
                  return Padding(
                    padding: const EdgeInsets.only(right: 8),
                    child: FilterChip(
                      label: Text(t.$2),
                      selected: sel,
                      onSelected: (_) {
                        setState(() => _type = t.$1);
                        _load();
                      },
                    ),
                  );
                }).toList(),
              ),
            ),
          ),
          if (!_loading)
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 16),
              child: StatBadge(label: 'Pending', value: '${_queue.length}', color: AppColors.warning),
            ),
          const SizedBox(height: 8),
          Expanded(
            child: _loading
                ? const Center(child: CircularProgressIndicator())
                : _queue.isEmpty
                    ? const EmptyState(icon: Icons.verified_outlined, message: 'Queue is empty for this category')
                    : ListView.builder(
                        padding: const EdgeInsets.all(12),
                        itemCount: _queue.length,
                        itemBuilder: (_, i) {
                          final item = _queue[i];
                          return Card(
                            child: ListTile(
                              title: Text(item[nameKey]?.toString() ?? '', style: const TextStyle(fontWeight: FontWeight.w600)),
                              subtitle: Text('Size ${item['size']} · Qty ${item['quantity']} · ${currencyFmt.format((item['price'] as num?) ?? 0)}'),
                              leading: Chip(
                                label: Text(_errorLabel(item['error']), style: const TextStyle(fontSize: 10)),
                                backgroundColor: AppColors.warning.withValues(alpha: 0.2),
                              ),
                              trailing: FilledButton(
                                onPressed: () => _approve(item),
                                style: FilledButton.styleFrom(backgroundColor: AppColors.success, minimumSize: const Size(72, 36)),
                                child: const Text('OK'),
                              ),
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
