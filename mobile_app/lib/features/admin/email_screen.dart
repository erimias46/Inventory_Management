import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../../core/providers/app_providers.dart';
import '../../core/theme/app_theme.dart';
import '../shared/pos_widgets.dart';

class EmailSubscribersScreen extends ConsumerStatefulWidget {
  const EmailSubscribersScreen({super.key});

  @override
  ConsumerState<EmailSubscribersScreen> createState() => _EmailSubscribersScreenState();
}

class _EmailSubscribersScreenState extends ConsumerState<EmailSubscribersScreen> {
  List<Map<String, dynamic>> _items = [];
  final _emailCtrl = TextEditingController();
  bool _loading = true;

  @override
  void dispose() {
    _emailCtrl.dispose();
    super.dispose();
  }

  Future<void> _load() async {
    setState(() => _loading = true);
    _items = await ref.read(opsRepositoryProvider).emails();
    setState(() => _loading = false);
  }

  @override
  void initState() {
    super.initState();
    _load();
  }

  Future<void> _add() async {
    final email = _emailCtrl.text.trim();
    if (email.isEmpty) return;
    await ref.read(opsRepositoryProvider).addEmail(email);
    _emailCtrl.clear();
    _load();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Email Subscribers')),
      body: Column(
        children: [
          Padding(
            padding: const EdgeInsets.all(12),
            child: Row(
              children: [
                Expanded(child: TextField(controller: _emailCtrl, decoration: const InputDecoration(labelText: 'Email'), keyboardType: TextInputType.emailAddress)),
                const SizedBox(width: 8),
                FilledButton(onPressed: _add, child: const Text('Add')),
              ],
            ),
          ),
          Expanded(
            child: _loading
                ? const Center(child: CircularProgressIndicator())
                : _items.isEmpty
                    ? const EmptyState(icon: Icons.email_outlined, message: 'No subscribers')
                    : ListView.builder(
                        itemCount: _items.length,
                        itemBuilder: (_, i) {
                          final row = _items[i];
                          return ListTile(
                            title: Text(row['email']?.toString() ?? ''),
                            trailing: IconButton(
                              icon: const Icon(Icons.delete_outline, color: AppColors.danger),
                              onPressed: () async {
                                await ref.read(opsRepositoryProvider).deleteEmail((row['id'] as num).toInt());
                                _load();
                              },
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
