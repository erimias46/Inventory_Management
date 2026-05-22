import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';

import '../../core/providers/app_providers.dart';
import '../../core/theme/app_theme.dart';
import '../auth/auth_repository.dart';

final connectionStatusProvider = FutureProvider<HealthStatus>((ref) async {
  return ref.read(authRepositoryProvider).connectionDiagnostics();
});

class ConnectionBanner extends ConsumerWidget {
  const ConnectionBanner({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final status = ref.watch(connectionStatusProvider);

    return status.when(
      data: (s) {
        if (s.fullyConnected) return const SizedBox.shrink();
        return Material(
          color: AppColors.danger.withValues(alpha: 0.9),
          child: InkWell(
            onTap: () => context.push('/settings'),
            child: Padding(
              padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 10),
              child: Row(
                children: [
                  const Icon(Icons.wifi_off, color: Colors.white, size: 20),
                  const SizedBox(width: 10),
                  Expanded(
                    child: Text(
                      s.message ?? 'Not connected to database',
                      style: const TextStyle(color: Colors.white, fontSize: 12),
                    ),
                  ),
                  const Text('Fix', style: TextStyle(color: Colors.white, fontWeight: FontWeight.w700)),
                ],
              ),
            ),
          ),
        );
      },
      loading: () => const SizedBox.shrink(),
      error: (_, __) => const SizedBox.shrink(),
    );
  }
}
