import 'package:flutter/material.dart';
import 'fit_track_page.dart';

void main() {
  runApp(const FitTrackApp());
}

class FitTrackApp extends StatelessWidget {
  const FitTrackApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Fit Track',
      home: const FitTrackPage(),
      debugShowCheckedModeBanner: false,
    );
  }
}
